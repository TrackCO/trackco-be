<?php

namespace App\Services;

use App\Enums\AccountRolesEnum;
use App\Enums\AccountType;
use App\Exceptions\ClientErrorException;
use App\Jobs\SendReferralEmail;
use App\Models\Business;
use App\Models\Integration;
use App\Models\User;
use App\Strategies\UserStrategy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\PasswordResetRequest;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\ReferralEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class UserService implements UserStrategy
{
    private static string $frontendUrl;
    private static string $guard = 'api';

    private FileUploaderService $fileUploaderService;

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
        self::$frontendUrl = config('app.frontend_url');
    }



    private static function user()
    {
        return Auth::guard(self::$guard)->user();
    }

    public function loginViaIntegration(array $credentials): array
    {
        $user = User::where('client_secret', $credentials['client_secret'])->first();

        if (!$user) throw new ClientErrorException('Invalid credentials provided.');
        $token = Auth::guard(self::$guard)->claims(['exp' => strtotime('+30 days')])->login($user);

        if(!$token) throw new ClientErrorException('Invalid credentials. Kindly try again.');
        $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');
        $expirationTime = date('Y-m-d H:i:s', $expiration);

        return ['user' => $user, 'token' => $token, 'expires_at' => $expirationTime];
    }

    public function login(array $credentials): array
    {
        $allow30Days = $credentials['allow_30_days'] ?? false;
        unset($credentials['allow_30_days']);
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) throw new ClientErrorException('Account not found.');

        $auth = Auth::guard(self::$guard);
        if($allow30Days) {
            $auth = $auth->claims(['exp' => strtotime('+30 days')]);
        }
        $token = $auth->attempt($credentials);

        if(!$token) throw new ClientErrorException('Invalid credentials. Kindly try again.');
        $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');
        $expirationTime = date('Y-m-d H:i:s', $expiration);

        return ['user' => $user, 'token' => $token, 'expires_at' => $expirationTime];
    }

    public function googleLogin(array $credentials): array
    {
        $email = $credentials['email'];
        $user = User::where('email', $email)->first();

        $googleAuthTokenVerifier = GoogleApiService::verify($credentials['token']);

        if(!$googleAuthTokenVerifier || $googleAuthTokenVerifier['email'] !== $email) throw new ClientErrorException('Invalid credentials. Kindly try again.');

        if(!$user){
            $user = self::createNewUserAccount($credentials);
        }

        $token = Auth::guard(self::$guard)->login($user);

        if(!$token) throw new ClientErrorException('Invalid credentials. Kindly try again.');
        $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');
        $expirationTime = date('Y-m-d H:i:s', $expiration);

        return ['user' => $user, 'token' => $token, 'expires_at' => $expirationTime];

    }

    private function createNewUserAccount(array $data)
    {
        return User::create([
            'full_name' => $data['name'] ?? '',
            'email' => $data['email'],
            'profile_picture' => $data['avatar'] ?? null,
            'account_type_id' => $data['account_type_id'] ?? AccountType::INDIVIDUAL->value,
            'phone' => $data['phone'] ?? '',
            'referral_code' => $data['referral_code'] ?? $this->generateReferralCode(),
            'password' => isset($data['password']) ? bcrypt($data['password']) : '',
            'country_id' => $data['country'] ?? 1,
            'referred_by' => $data['referred_by'] ?? null
        ]);
    }

    public function register(array $credentials, bool $viaIntegration = false): User
    {
        $referredBy = $credentials['referral_code'] ?? null;
        if($referredBy){
            $referredBy = User::where('referral_code', $referredBy)->first();
            if(!$referredBy){
                throw new ClientErrorException('Invalid referral code!');
            }
        }
        $userData = [
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'account_type_id' => $credentials['account_type'] ?? null,
            'password' => $credentials['password'],
            'country_id' => $credentials['country'] ?? null,
            'phone' => $credentials['phone'] ?? null,
            'referral_code' => $this->generateReferralCode(),
            'referred_by' => $referredBy ? $referredBy->id : null
        ];

        $user = self::createNewUserAccount($userData);

        if($viaIntegration){
            $source = Integration::where('app_secret_key', request()->header('X-App-Secret-Key'))->first();
            $user->client_secret = bcrypt(generateRandomCharacters(40));
            $user->source = $source->source;
            $user->save();
        }
        if($referredBy){ // Process referral assignment
            $referredBy->assignReferralBonus();
        }

        if($credentials['account_type'] && ($credentials['account_type'] == AccountType::BUSINESS->value)) return self::createBusinessAccount($user, $credentials);
        return $user;
    }

    /**
     * @return string
     */
    private function generateReferralCode(): string
    {
        $refCode = generateRandomCharacters(15);
        if(User::where('referral_code', $refCode)->exists()){
            return $this->generateReferralCode();
        }

        return $refCode;
    }

    public function logout(): mixed
    {
        Auth::guard(self::$guard)->logout();
        return true;
    }

    private static function createBusinessAccount(User $user, array $data): User
    {
        $user->role_id = AccountRolesEnum::BUSINESS_OWNER->value;
        $business = Business::create([
            'name' => $data['company_name'],
            'industry' => $data['industry'] ?? null,
            'created_by' => $user->id
        ]);
        $user->business_id = $business->id;
        $user->save();
        return $user;
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws ClientErrorException
     */
    public function managePassword($request): \Illuminate\Contracts\Auth\Authenticatable|null
    {
        $data = $request->toArray();
        $password = $data['password'] ?? null;
        $oldPassword = $data['old_password'] ?? null;
        if(!$password && !$oldPassword) throw new ClientErrorException('No valid data provided for update.');

        $user = self::user();

        if(!Hash::check($oldPassword, $user->password)) throw new ClientErrorException('Incorrect old password provided.');

        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function manageUserInfo($request)
    {
        $data = $request->toArray();
        $user = self::user();
        if (isset($data['contact_no'])) {
            $user->phone = $data['contact_no'];
        }

        if (isset($data['country'])) {
            $user->country_id = $data['country'];
        }

        if ($user->business) {
            $business = $user->business;

            if (isset($data['website'])) {
                $business->website_url = $data['website'];
            }

            if (isset($data['number_of_employees'])) {
                $business->no_of_employees = (int)$data['number_of_employees'];
            }

            $business->save();
        }

        $user->save();
        return $user;
    }

    public function manageUserPictureUpdate($request)
    {
        $file = $request->file;
        if(!$file) throw new ClientErrorException('No file provided.');
        $purpose = $request['purpose'] ?? null;
        $user = self::user();

        $uploadedFile =  $this->fileUploaderService->uploadFileToLocal($file, 'uploads/profile');

        if($purpose === 'profile'){
            $user->profile_picture = asset($uploadedFile);
            $user->save();
        }elseif($purpose === 'logo'){
            if(!$business = $user->business) throw new ClientErrorException('No business attached to account.');

            $business->logo_url = asset($uploadedFile);
            $business->save();

        }else throw new ClientErrorException('Unknown purpose.');

        return $user;
    }

    public function sendResetLink(array $data)
    {
        $email = $data['email'];
        $user = User::where('email', $email)->first();

        if (!$user) throw new ClientErrorException('User not found with that email address.');

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $user->notify(new ResetPasswordNotification($token, self::$frontendUrl));

        return [
            'status' => 'success',
            'message' => 'Password reset link has been sent to your email.'
        ];
    }

    public function resetPassword($data)
    {
        $token = $data['token'];
        $email = $data['email'];
        $tokenData = PasswordResetRequest::where('token', $token)->first();

        if (!$tokenData || $tokenData->email !== $email) {
            throw new ClientErrorException('Invalid token or email.');
        }

        if (now()->diffInHours($tokenData->created_at) > config('app.account_activation_timeout')) {
            throw new ClientErrorException('This password reset link has expired.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new ClientErrorException('User not found.');
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        PasswordResetRequest::where('email', $email)->forceDelete();

        return [
            'status' => 'success',
            'message' => 'Password has been successfully reset.',
            'tokenData' => $tokenData
        ];
    }

    public function activateAccount(array $requestData)
    {
        $password = $requestData['password'];
        $name = $requestData['name'];
        $token = $requestData['token'];
        $email = $requestData['email'];
        $user = User::where('activation_token', $token)
                ->where('email', $email)
                ->first();

        if (!$user || !$user->isActivationTokenValid()) {
            throw new ClientErrorException('Invalid or expired activation link.');
        }

        $user->activation_token = null;
        $user->full_name = $name;
        $user->password = bcrypt($password);
        $user->save();
        return true;
    }

    public function sendReferralEmail(array $data): array
    {
        $users = $data['users'] ?? [];

        if(!$users) throw new ClientErrorException('No user details provided.');

        $referrer = self::user();
        if (!$referrer->referral_code) {
            $referrer->referral_code = $this->generateReferralCode();
            $referrer->save();
            $referrer->refresh();
        }
        $accountType = $referrer->account_type_id == AccountType::INDIVIDUAL ? 'individual' : 'business';
        $referralLink = config('app.frontend_url')."/auth/signup/{$accountType}?ref={$referrer->referral_code}";

        try {
            foreach ($users as $user) SendReferralEmail::dispatch($user, $referrer->full_name, $referralLink);
        } catch (\Exception $e) {
            Log::error("Mail Error:: " . $e->getMessage());
            throw new ClientErrorException("Something went wrong while sending referral email. Kindly try again later.");
        }

        return [
            'status' => 'Success',
            'message' => "Referral emails has been sent successfully.",
        ];

    }

}

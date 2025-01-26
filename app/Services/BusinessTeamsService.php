<?php

namespace App\Services;

use App\Enums\AccountRolesEnum;
use App\Enums\AccountType;
use App\Exceptions\ClientErrorException;
use App\Models\Business;
use App\Models\User;
use App\Support\Traits\GenericServicesTrait;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TeamMemberInviteNotification;
use Illuminate\Support\Str;
use Carbon\Carbon;
class BusinessTeamsService
{
    use GenericServicesTrait;

    public function createTeam(array $requestData)
    {
        $user = self::user();
        $business = $user->business;

        if(!$business) throw new ClientErrorException('Unable to process your request.');
        $companyName = $business->name;
        foreach ($requestData['members'] as $data) { 
            $token = Str::random(60);
            $activationExpiry = Carbon::now()->addHours(config('app.account_activation_timeout'));
            $newUser = User::create([
                'full_name' => explode('@', $data['email'])[0],
                'email' => $data['email'],
                'country_id' => $data['country'],
                'business_id' => $business->id,
                'phone' => isset($data['phone']) ? $data['phone'] : '',
                'account_type_id' => AccountType::BUSINESS->value,
                'role_id' => AccountRolesEnum::EMPLOYEE->value,
                'activation_token' => $token,
                'activation_token_expires_at' => $activationExpiry,
                'referral_code' => generateRandomCharacters(15),
            ]);
            $newUser->notify(new TeamMemberInviteNotification($newUser, $token, $companyName));
        }
        return $user;
    }

    public function listTeamMembers()
    {
        $user = self::user();
        $business = $user->business;
        return $business->teamMembers()
            ->where('id', '!=', $user->id)
            ->get();
    }

    public function update($userId, $requestData)
    {
        $user = User::find($userId);
        if(!$user) throw new ClientErrorException('User not found.');

        $user->update([
            'full_name' => $requestData['name'],
            'country_id' => $requestData['country'],
        ]);

        return $user;
    }

    public function removeTeam($userId)
    {
        $user = User::find($userId);

        if(!$user) throw new ClientErrorException('Unable to process your request.');

        $user->business_id = null;
        $user->account_type_id = AccountType::INDIVIDUAL->value;
        $user->save();

        return "Team member has been removed";
    }

    public function detail($id)
    {
        $user =  User::find($id);
        if(!$user) throw new ClientErrorException('User not found.');
        return $user;
    }




}

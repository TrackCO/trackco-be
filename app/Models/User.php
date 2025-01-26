<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'password',
        'is_verified',
        'role_id',
        'account_type_id',
        'business_id',
        'status',
        'country_id',
        'referral_code',
        'profile_picture',
        'activation_token',
        'activation_token_expires_at',
        'points_earned',
        'referred_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activation_token_expires_at' => 'datetime',
    ];

    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return []; }


    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function footprintHistories()
    {
        return $this->hasMany(CarbonFootprint::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function emissionGoals(): HasMany
    {
        return $this->hasMany(CarbonEmissionGoal::class, 'user_id');
    }

    public function totalEmissionsCalculated(): int
    {
        return $this->emissionGoals()->count();
    }

    public function currentEmissionGoal(): Model | null
    {
        return $this->emissionGoals()->latest()->first();
    }

    public function latestCarbonEmission(): Model | null
    {
        return $this->footprintHistories()->latest()->first();
    }

    /**
     * Check if the activation token is valid.
     *
     * @return bool
     */
    public function isActivationTokenValid(): bool
    {
        return $this->activation_token && $this->activation_token_expires_at && Carbon::now()->lessThanOrEqualTo($this->activation_token_expires_at);
    }

    /**
     * Clear the activation token and expiration date after successful activation.
     *
     * @return void
     */
    public function clearActivationToken(): void
    {
        $this->update([
            'activation_token' => null,
            'activation_token_expires_at' => null,
        ]);
    }

    public static function byReferralCode($code)
    {
        self::where('referral_code', $code)->first();
    }

    public function assignReferralBonus()
    {
        return $this->increment('points_earned', config('app.referral_bonus_points'));
    }

    /**
     * @param $query
     * @param array $searchData
     * @return mixed
     */
    public function scopeBySearch($query, array $searchData)
    {
        $search = $searchData['search'] ?? '';
        $from = $searchData['from'] ?? '';
        $to = $searchData['to'] ?? '';
        return $query->where(function ($query) use ($from, $to, $search) {

            if (!empty($from) && !empty($to)) $query->whereDate('users.created_at', '>=', $from)
                ->whereDate('users.created_at', '<=', $to);

            if (!empty($search)) $query->orWhere('users.full_name', 'LIKE', "%{$search}%")->orWhere('users.email', 'LIKE', "%{$search}%");

        })->orderBy('users.created_at', 'desc');
    }
}

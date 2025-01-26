<?php

namespace App\Http\Resources;

use App\Enums\AccountType;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'client_secret' => $this['client_secret'],
            'full_name' => $this['full_name'],
            'email' => $this['email'],
            'phone' => $this['phone'],
            'referral_code' => $this['referral_code'],
            'points_earned' => $this['points_earned'],
            'profile_picture' => $this['profile_picture'],
            'created_at' =>  Carbon::parse($this['created_at'])->format('jS M, Y'),
            'role' => $this->role,
            'account_type' => AccountType::getDescription($this['account_type_id']),
            'country' => CountryResource::make($this['country']),
            'business' => $this->business,
            'totalEmissions' => $this->totalEmissionsCalculated()
        ];
    }
}

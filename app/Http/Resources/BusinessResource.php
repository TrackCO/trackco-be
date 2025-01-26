<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
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
            'name' => $this['name'],
            'industry' => $this['industry'],
            'createdBy' => new UserResource($this->owner),
            'totalEmployees' => $this->employees()->count(),
            'created_at' => Carbon::parse($this['created_at'])->format('jS M Y'),
        ];
    }
}

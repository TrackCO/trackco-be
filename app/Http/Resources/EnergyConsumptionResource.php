<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnergyConsumptionResource extends JsonResource
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
            'number_of_employees' => $this->number_of_employees,
            'electricity_usage' => $this->electricity_usage,
            'consumptions' => EnergyConsumptionSourceResource::collection($this->energyConsumptionSources)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarbonFootprintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $countryAverage = getCountryAverageByName($this?->country?->name);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'energy_emission' => $this->energy_emission,
            'transportation_emission' => $this->transportation_emission,
            'lifestyle_emission' => $this->lifestyle_emission,
            'total_emission' => $this->total_emission,
            'created_at' => $this->created_at,
            'country' => CountryResource::make($this->country),
            'countryAverage' => $countryAverage ? $countryAverage['co2_yearly'] : 0,
            'energyConsumptions' => new EnergyConsumptionResource($this->energyConsumption),
            'transportationEmission' => new EmissionTransportationResource($this->transportationEmission),
            'lifestyleEmission' => new LifestyleEmissionResource($this->lifestyleEmission)
        ];
    }
}

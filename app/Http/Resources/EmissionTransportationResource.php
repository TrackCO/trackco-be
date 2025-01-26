<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmissionTransportationResource extends JsonResource
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
            'flight_very_long_max' => $this['flight_very_long_max'],
            'flight_very_long_min' => $this['flight_very_long_min'],
            'flight_long_max' => $this['flight_long_max'],
            'flight_long_min' => $this['flight_long_min'],
            'flight_medium_max' => $this['flight_medium_max'],
            'flight_medium_min' => $this['flight_medium_min'],
            'flight_short_max' => $this['flight_short_max'],
            'flight_short_min' => $this['flight_short_min'],
            'enabled_mode' => !is_null($this['enabled_mode']) ? json_decode($this['enabled_mode'], true) : [],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LifestyleEmissionResource extends JsonResource
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
            'id' => $this->id,
            'period' => $this->period,
            'paper_products_spending' => $this->paper_products_spending,
            'banking_finance' => $this->banking_finance,
            'recreational_activities' => $this->recreational_activities,
            'insurance' => $this->insurance,
            'education' => $this->education,
            'pharmaceuticals' => $this->pharmaceuticals,
            'diet_reference' => $this->diet_reference,
            'currency' => new CurrencyResource($this->selectedCurrency),
            'preferredDiet' => $this->preferredDiet
        ];
    }
}

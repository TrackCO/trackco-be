<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarbonEmissionDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bike_rate' => ['required', 'numeric'],
            'bike_period' => ['required', 'string'],
            'city_bus_rate' => ['required', 'numeric'],
            'city_bus_period' => ['required', 'string'],
            'train_rate' => ['required', 'numeric'],
            'train_period' => ['required', 'string'],
            'walk_rate' => ['required', 'numeric'],
            'walk_period' => ['required', 'string'],
            'car_details' => ['nullable', 'array'],
            'car_details.*' => ['nullable', 'array'],
            'car_details.*.type' => ['nullable', 'string'],
            'car_details.*.annual_mileage' => ['nullable', 'numeric'],
            'car_details.*.average_consumption' => ['nullable', 'numeric'],
            'flight_very_long_max' => ['nullable', 'numeric'],
            'flight_very_long_min' => ['nullable', 'numeric'],
            'flight_long_max' => ['nullable', 'numeric'],
            'flight_long_min' => ['nullable', 'numeric'],
            'flight_medium_max' => ['nullable', 'numeric'],
            'flight_medium_min' => ['nullable', 'numeric'],
            'flight_short_max' => ['nullable', 'numeric'],
            'flight_short_min' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'numeric', 'exists:currencies,id'],
//            'period' => ['nullable', 'string'],
            'paper_based_products' => ['nullable', 'numeric'],
            'banking_and_finance' => ['nullable', 'numeric'],
            'motor_vehicles' => ['nullable', 'numeric'],
            'hotels_restaurants' => ['nullable', 'numeric'],
            'insurance' => ['nullable', 'numeric'],
            'education' => ['nullable', 'numeric'],
            'pharmaceuticals' => ['nullable', 'numeric'],
            'cloths_and_shoes' => ['nullable', 'numeric'],
            'recreational_activities' => ['nullable', 'numeric'],
            'furniture' => ['nullable', 'numeric'],
            'preferred_diet' => ['nullable', 'string'],
            'no_of_employees' => ['nullable', 'integer', 'min:1'],
            'location' => ['required', 'integer', 'exists:countries,id'],
            'electricity_consumption' => ['required', 'integer'],
            'natural_gas' => ['required', 'integer'],
            'natural_gas_unit' => ['required', 'exists:units,id'],
            'heating_oil' => ['required', 'integer'],
            'heating_oil_unit' => ['required', 'exists:units,id'],
            'coal' => ['required', 'integer'],
            'coal_unit' => ['required', 'exists:units,id'],
            'lpg' => ['required', 'integer'],
            'lpg_unit' => ['required', 'exists:units,id'],
            'wooden_pellets' => ['required', 'integer'],
            'wooden_pellets_unit' => ['required', 'exists:units,id'],
            'propane' => ['required', 'integer'],
            'propane_unit' => ['required', 'exists:units,id'],
            'calculatedValues' => ['required', 'array'],
            'calculatedValues.*' => ['required', 'numeric'],
            'emissionTitle' => ['required', 'string'],
            'house_type' => ['nullable', 'integer', 'exists:house_types,id'],
            'size' => ['nullable', 'integer'],
            'solar' => ['nullable', 'integer'],
            'wind' => ['nullable', 'integer'],
            'hydro_power' => ['nullable', 'integer'],
            'nuclear' => ['nullable', 'integer'],
            'filterFromMonth' => ['required', 'integer'],
            'filterToMonth' => ['required', 'integer']

        ];
    }
}

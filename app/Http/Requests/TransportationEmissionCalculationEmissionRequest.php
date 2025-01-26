<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportationEmissionCalculationEmissionRequest extends FormRequest
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
        ];
    }
}

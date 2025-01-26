<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnergyEmissionCalculationEmissionRequest extends FormRequest
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

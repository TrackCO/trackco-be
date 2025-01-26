<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LifestyleEmissionCalculationEmissionRequest extends FormRequest
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
            'currency' => ['nullable', 'numeric', 'exists:currencies,id'],
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

        ];
    }
}

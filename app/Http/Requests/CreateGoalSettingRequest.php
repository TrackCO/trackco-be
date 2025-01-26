<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGoalSettingRequest extends FormRequest
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
            'min_energy_emission' => ['nullable', 'numeric'],
            'max_energy_emission' => ['nullable', 'numeric'],
            'min_transportation_emission' => ['nullable', 'numeric'],
            'max_transportation_emission' => ['nullable', 'numeric'],
            'min_lifestyle_emission' => ['nullable', 'numeric'],
            'max_lifestyle_emission' => ['nullable', 'numeric'],
        ];
    }
}

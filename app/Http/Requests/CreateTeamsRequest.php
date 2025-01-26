<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamsRequest extends FormRequest
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
            'members' => 'required|array',
            'members.*.email' => ['required', 'email', 'unique:users,email'],
            'members.*.country' => ['required', 'exists:countries,id'],
        ];
    }

    public function messages()
    {
        return [
            'members.*.email.required' => 'The email address is required for each member.',
            'members.*.email.email' => 'Please provide a valid email address for each member.',
            'members.*.email.unique' => 'The email address ":input" has already been added. Please use a different email address.',
            'members.*.country.required' => 'Please select a country for each member.',
            'members.*.country.exists' => 'The selected country does not exist.',
        ];
    }
}

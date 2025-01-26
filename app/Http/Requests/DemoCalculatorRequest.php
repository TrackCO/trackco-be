<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemoCalculatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'electricity_consumption' => ['nullable', 'numeric'],
            'location' => ['required', 'exists:countries,id'],
            'sendReport' => ['required', 'boolean'],
            'company_name' => ['required_if:sendReport,true', 'string'],
            'first_name' => ['required_if:sendReport,true', 'string'],
            'last_name' => ['required_if:sendReport,true', 'string'],
            'account_type' => ['required_if:sendReport,true', 'string'],
            'email' => ['required_if:sendReport,true', 'email'],
            'phone' => ['required_if:sendReport,true', 'string'],
            'industry' => ['required_if:sendReport,true', 'string', 'min:2'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required_if' => 'First name is required.',
            'last_name.required_if' => 'Last name is required.',
            'email.required_if' => 'Email is required.',
            'company_name.required_if' => 'Company name is required.',
            'phone.required_if' => 'Phone number field is required.',
            'industry.required_if' => 'Industry field is required.',
            'password.required_if' => 'Password is required.',
        ];
    }
}

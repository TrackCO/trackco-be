<?php

namespace App\Http\Requests;

use App\Enums\AccountType;
use Illuminate\Foundation\Http\FormRequest;

class IntegrationInitiationRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3'],
            'account_type' => ['required', 'exists:account_types,id'],
            'company_name' => ['required_if:account_type,'.AccountType::BUSINESS->value, 'string', 'min:2', 'unique:businesses,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'industry' => ['required_if:account_type,'.AccountType::BUSINESS->value, 'string', 'min:2'],
            'country' => ['required', 'exists:countries,id'],
            'referral_code' => ['nullable', 'string', 'min:2'],
            'phone' => ['required', 'phone:AUTO'],
        ];
    }

    public function messages(){
        return [
            'phone.phone' => 'The phone field must be a valid number.',
            'company_name.required_if' => 'The company name is required.',
            'industry.required_if' => 'The industry field is required.',
        ];
    }
}

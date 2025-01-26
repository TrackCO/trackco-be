<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'available_tonnes' => ['required', 'numeric'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country_id' => ['required', 'exists:countries,id'],
            'size' => ['required', 'numeric'],
            'type' => ['required', 'string'],
            'project_category_id' => ['required', 'exists:project_categories,id'],
            'developer_name' => ['required', 'string'],
            'eligibility' => ['required', 'string'],
            'standard' => ['required', 'string'],
            'methodology' => ['required', 'string'],
            'additional_certificates' => ['required', 'string'],
            'cbb_validator' => ['required', 'string'],
            'project_validator' => ['required', 'string'],
            'issue_date' => ['required', 'date'],
        ];
    }
}

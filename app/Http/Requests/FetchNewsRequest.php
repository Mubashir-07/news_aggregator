<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponseHelper;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FetchNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'provider' => 'required',
            'from_date' => 'date',
            'to_date' => 'date',
            'page' => 'numeric',
            'pageSize' => 'numeric',
        ];
    }
//
//    public function failedValidation(Validator $validator)
//    {
//        return ApiResponseHelper::error('Validation failed', $validator->errors(), 422);
//    }
}

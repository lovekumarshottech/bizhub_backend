<?php

namespace App\Http\Requests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

class ServiceFiltersRequest extends FormRequest
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
            'amount_from' => 'nullable|integer',
            'amount_to' => 'nullable|integer',
            'q' => 'nullable|regex:/^[A-Za-z0-9 ]+$/',
            'category_id' => 'nullable|exists:categories,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'distance' => 'nullable|integer'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code'   => 422,
            'message'   => 'Fields are missing or invalid.',
            'data'      => $validator->errors()
        ], 422));
    }
}

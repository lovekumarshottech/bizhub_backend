<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
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
            'title' => ['required','min:10','max:100'],
            'description' => ['required'],
            'address' => ['required'],
            'latitude' => ['required'],
            'is_negotiable' => ['required'],
            'longitude' => ['required'],
            'start_date' => ['required'],
            'no_of_ppl' => ['required'],
            'amount' => ['required','regex:/^\d+(\.\d{1,2})?$/'],
            'category_id' => 'required|not_in:0'
        ];
    }


    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.not_in' => 'Please select job category',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'user_id' => Auth::user()->id,
            'no_of_ppl' => $this->people_required
        ]);
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

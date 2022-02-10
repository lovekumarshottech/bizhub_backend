<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class RegisterUserRequest extends FormRequest
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
            'first_name' => ['required', 'alpha_spaces', 'max:30'],
            'last_name' => ['required', 'alpha_spaces', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => 'required|min:8|max:255|confirmed',
            'phone' => 'required|numeric|digits:10|unique:users',
            'image' => 'required|base64dimensions:min_width=100,min_height=200',
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
            'first_name.alpha_spaces' => 'First name field should be alphabatic',
            'last_name.alpha_spaces' => 'Last name field should be alphabatic',
            'email.required' => 'Email field required',
            'email.email' => 'In-valid email format',
            'email.unique' => 'Email already exists',
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

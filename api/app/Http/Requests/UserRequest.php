<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => "required|max:255|email|unique:users,email,{$this->id}",
            'password' => [
                'required',
                'min:8',              // at least 8 characters
                'regex:/[a-z]/',      // at least 1 lowercase letter
                'regex:/[A-Z]/',      // at least 1 uppercase letter
                'regex:/[@$!%*#?&]/'  // at least 1 special character
            ]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'password.min' => 'The password must be at least :min characters',
            'password.regex' => 'The password field must have at least'
                . ' 1 lowercase letter,'
                . ' 1 uppercase letter and 1 special character.'
        ];
    }
}

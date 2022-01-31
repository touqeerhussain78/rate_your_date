<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends ApiRequest
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
            'current_password'     => 'required|string|min:8',
            'password'     => 'required|string|min:8',
            'password_confirmation' =>   'required|same:password',
        ];
    }


    public function messages()
    {
        return [
            'current_password.required' => 'Current Password is required.',
            'password.required' => 'Password is required.',
            'password_confirmation.required' => 'Password Confirmation is required.',
            'password_confirmation.same' => 'Password And Confirmation Does not match.',
        ];
    }
}

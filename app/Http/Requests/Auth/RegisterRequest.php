<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends ApiRequest
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
            'first_name'    => 'required',
            'last_name'    => 'required',
            'phone'    => 'required',
            'email'        => 'required|unique:users,email',
            'password'     => 'required|string|min:8',
            'password_confirmation' =>   'required|same:password',
            'role_id'    => 'required|exists:roles,id',
        ];
    }


    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.unique' => 'Email is already taken!',
            'first_name.required' => 'First Name is required!',
            'last_name.required' => 'Last Name is required!',
            'password.required' => 'Password is required!',
            'password_confirmation.required' => 'Password Confirmation is required!',
            'password_confirmation.same' => 'Password And Confirmation Does not match!',
            'role_id.required'    => 'Role id is required!',
            'role_id.exists'    => 'Role Does not exist!',
        ];
    }


}

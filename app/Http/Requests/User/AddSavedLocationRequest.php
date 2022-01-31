<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddSavedLocationRequest extends FormRequest
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
            // 'user_id'     => 'required',
            'name'     => 'required|string',
            'address'     => 'nullable|string',
            'latitude' =>   'required',
            'longitude' =>   'required',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'latitude.required' => 'Latitude is required.',
            'longitude.required' => 'Longitude is required',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddTripRequest extends ApiRequest
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
            // 'user_id'     => 'required|integer',
            // 'driver_id'     => 'nullable|integer',
            // 'vehicle_id' =>   'required',
            'pickup_name' =>   'required',
            'pickup_address' =>   'required',
            'dropoff_name' =>   'required',
            'dropoff_address' =>   'required',
            'start_time' =>   'required',
            'end_time' =>   'required',
            'trip_type' => 'required',
            'promotion_id' =>   'nullable|integer',
            // 'is_cancelled' =>   'required',
            'is_paid' =>   'required',
            'amount' =>   'nullable',
            // 'status' =>   'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First Name is required.',
            'last_name.required' => 'Last Name is required.',
            'email.required' => 'Email is required.',
            'message.required' => 'Message is required.',
        ];
    }
}

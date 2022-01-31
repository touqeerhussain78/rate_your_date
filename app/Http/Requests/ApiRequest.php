<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
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
     * Sends JSON error response upon failure
     *
     * @return Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator) {

        $errors = (new ValidationException($validator))->errors();

        $response = [
            'message' => __('Validation errors'),
            'errors' => $errors,
        ];
        
        throw new HttpResponseException(response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function rules() {
        return [];
    }
}

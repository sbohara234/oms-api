<?php

namespace App\Trait;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
trait JsonApiResponseTrait
{
    public function successResponse($data, $message = null, $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function messageResponse($message = null, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }

    public function errorResponse($message, $statusCode = 400)
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }

    public function validationErrorResponse($errors, $message = 'Validation error', $statusCode = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}

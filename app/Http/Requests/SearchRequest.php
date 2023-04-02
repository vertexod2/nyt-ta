<?php

namespace App\Http\Requests;

use App\Rules\ISBNArray;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'string|min:2',
            'title' => 'string|min:2',
            'isbn' => ['array', new ISBNArray()],
            'offset' => 'integer|multiple_of:20',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }
}

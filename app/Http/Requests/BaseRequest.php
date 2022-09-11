<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
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
     * Throw an exception when validation fails.
     */
    public function failedValidation(Validator $validator)
    {
        throw new ValidationFailException($validator->errors()->all());
    }
}

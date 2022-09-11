<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCode;
use Exception;

class ValidationFailException extends Exception
{
    public function __construct(array $errors =  ['invalid'])
    {
        $this->errors = $errors;
    }
    public function render()
    {
        return response()->json(response_format_data($this->errors, HttpStatusCode::UNPROCESSABLE_ENTITY), HttpStatusCode::UNPROCESSABLE_ENTITY);
    }
}

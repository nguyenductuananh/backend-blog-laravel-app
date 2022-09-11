<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCode;
use Exception;

class NotFoundException extends Exception

{
    public function render()
    {
        $responseMessage = empty($this->message) ? __('response-message.not-found') : $this->message;
        $responseCode = empty($this->code) ? HttpStatusCode::NOT_FOUND : $this->code;
        return response()->json(response_format_data(["message" => $responseMessage], $responseCode, true), $responseCode);
    }
}

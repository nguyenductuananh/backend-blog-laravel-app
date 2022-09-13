<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCode;
use Exception;

class InternalErrorException extends Exception
{
    public function __construct($previous = null)
    {
        $message = __('response-message.internal-error');
        $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
        parent::__construct($message, $code, $previous);
    }
    
    public function render()
    {
        $responseMessage = ['message' => $this->message];
        $responseCode = $this->code;
        if ($_ENV["APP_DEBUG"]) {
            $responseMessage['errors'] = $this->getPrevious()->getMessage();
        }
        return response()->json(response_format_data($responseMessage, $responseCode, true), $responseCode);
    }
}

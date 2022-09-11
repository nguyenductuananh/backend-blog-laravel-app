<?php

use App\Enums\HttpStatusCode;

function response_format_data(mixed $response_data, int $code, bool $pure_response = false)
{
    if (!boolval($response_data) || !boolval($code)) {
        return [];
    }
    if ($pure_response) {
        return array_merge($response_data, ['statusCode' => $code]);
    }

    $response_final = ['statusCode' => $code];

    switch ($code) {
        case HttpStatusCode::INTERNAL_SERVER_ERROR:
        case HttpStatusCode::FORBIDDEN:
        case HttpStatusCode::BAD_REQUEST:
        case HttpStatusCode::CREATED:
            $response_final['message'] = $response_data;
            break;

        case HttpStatusCode::UNPROCESSABLE_ENTITY:
            $response_final['errors'] = $response_data;
            break;

        case HttpStatusCode::SUCCESS:
            $response_final['data'] = $response_data;
            break;

        default:
            break;
    }

    return  $response_final;
}

<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HttpStatusCode extends Enum
{
    const SUCCESS = 200;
    const CREATED = 201;

    const BAD_REQUEST = 400;
    const FORBIDDEN = 401;
    const NOT_FOUND = 404;
    const UNPROCESSABLE_ENTITY = 422;

    const INTERNAL_SERVER_ERROR = 500;
}

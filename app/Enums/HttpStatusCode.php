<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESS()
 * @method static static BAD_REQUEST()
 * @method static static FORBIDDEN()
 * @method static static UNPROCESSABLE_ENTITY()
 * @method static static INTERNAL_SERVER_ERROR()
 */
final class HttpStatusCode extends Enum
{
    const SUCCESS = 200;
    const BAD_REQUEST = 400;
    const FORBIDDEN = 401;
    const UNPROCESSABLE_ENTITY = 422;
    const INTERNAL_SERVER_ERROR = 500;
}

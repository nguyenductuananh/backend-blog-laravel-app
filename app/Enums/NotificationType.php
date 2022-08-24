<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static RATE()
 * @method static static VOTE()
 * @method static static COMMENT()
 */
final class NotificationType extends Enum
{
    const RATE = 0;
    const VOTE = 1;
    const COMMENT = 2;
}

<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeagueType extends Enum
{
    const MENS =   0;
    const LADIES =   1;
    const JUNIORS = 2;

    public static function getDescription($value): string
    {
        switch ($value)
        {
            case self::MENS:
                return 'Mens';
            case self::LADIES:
                return 'Ladies';
            case self::JUNIORS:
                return 'Juniors';
            default:
                return 'Error No league Type.';
        }
    }
}



<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class UserType extends Enum
{
    const PLAYER =   0;
    const CLUB =   1;
    const LEAGUE = 2;
    const ADMIN = 3;

    /**
     * Gets the string description of the Enum.
     *
     * @param int $value Value of the enum
     * @return string A string of the league type.
     */
    public static function getDescription($value): string
    {
        switch ($value)
        {
            case self::PLAYER:
                return 'Player';
            case self::CLUB:
                return 'Club Secretary';
            case self::LEAGUE:
                return 'League Secretary';
            case self::ADMIN:
                return 'Admin';
            default:
                return 'Error No User Type.';
        }
    }
}



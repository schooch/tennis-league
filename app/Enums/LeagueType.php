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

    /**
     * gets the description but in all lower case.
     */
    public static function getLowerDescription($value): string
    {
        return strtolower(LeagueType::getDescription($value));
    }

    public static function getValueFromString($input)
    {
        $x = LeagueType::toArray();
        foreach ($x as $key => $val) {
            if(strtoupper($key) == strtoupper($input))
            {
                return $val;
            }
        }
        return null;
    }
}



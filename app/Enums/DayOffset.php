<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Collection;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DayOffset extends Enum
{
    const MONDAY =   0;
    const TUESDAY =   1;
    const WEDNESDAY = 2;
    const THURSDAY = 3;
    const FRIDAY = 4;
    const SATURDAY = 5;
    const SUNDAY = 6;

    public static function getTitleKeys()
    {
        $collection = new Collection(DayOffset::getKeys());
        $toReturn = $collection->map(function($item, $key){
            return ucwords(strtolower($item));
        });
        return $toReturn;
    }
}


<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EnoughPairs implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     * Each teams needs at least one pair, so if the first team has a pair, it changes the bool.
     * If the second team has the paid and the bool is True then it returns True.
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $homeHasPlayer = False;
        foreach ($value as $key => $v)
        {
            foreach ($v as $key => $player)
            {
                if(isset($player))
                {
                    if($homeHasPlayer)
                    {
                        return True;
                    }
                    else
                    {
                        $homeHasPlayer = True;
                    break;
                    }
                }
            }
        }
        return False;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Each team needs at least 1 pair.';
    }
}

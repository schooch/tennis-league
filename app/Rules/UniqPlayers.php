<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqPlayers implements Rule
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
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $merged = array_merge($value['home'], $value['away']);
        foreach ($merged as $key => $v)
        {
            if(!isset($v))
            {
                unset($merged[$key]);
            }
        }
        $counts = array_count_values($merged);
        foreach ($counts as $key => $v)
        {
            if($v != 1)
            {
                return False;
            }
        }
        return True;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The same player can\'t play twice.';
    }
}

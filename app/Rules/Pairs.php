<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Pairs implements Rule
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
        foreach ($value as $key => $v) {
            if($v['a1'] xor $v['a2'])
            {
                return False;
            }
            if($v['b1'] xor $v['b2'])
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
        return 'Players can\'t play on their own.';
    }
}

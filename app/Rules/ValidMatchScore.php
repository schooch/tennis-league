<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMatchScore implements Rule
{
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function tie_break_check($winningScore, $won, $loss)
    {
        if($won < $winningScore)
        {
            $this->message = "Winning score must be " . $winningScore . " or higher.";
            return False;
        }
        if($won >= $winningScore)
        {
            if($won - $loss != 2)
            {
                $this->message = 'If over the winning score, the loosing score can only be 2 less than it.';
                return False;
            }
        }
        return True;
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
        foreach ($value as $key => $match)
        {
            $skipped = False;
            $homeWins = 0;
            $awayWins = 0;
            foreach ($match as $key => $set)
            {
                $home = intval($set['home']);
                $away = intval($set['away']);
                if($home > $away)
                {
                    $homeWins++;
                }
                else
                {
                    $awayWins++;
                }
                $won = max($home, $away);
                $loss = min($home, $away);
                if($set['home'] == 0 && $set['away'] == 0)
                {
                    $skipped = True;
                    continue;
                }
                else
                {
                    if($skipped)
                    {
                        $this->message = "Set skipped";
                        return False;
                    }
                }
                if($won == $loss)
                {
                    $this->message = "Winning score and loosing score can't be the same.";
                    return False;
                }
                if($key == 3)
                {
                    if ($homeWins != $awayWins)
                    {
                        $this->message = "Third set should only be played if previous sets are equal.";
                        return False;
                    }
                    if(!$this->tie_break_check(10, $won, $loss))
                    {
                        return False;
                    }
                }
                else if(isset($set['tie']))
                {
                    if(!$this->tie_break_check(7, $won, $loss))
                    {
                        return False;
                    }
                }
                else
                {
                    if($won < 6)
                    {
                        $this->message =  "Winning score must be 6 or 7.";
                        return False;
                    }
                    if($won > 7)
                    {
                        $this->message = "Winning score must be 6 or 7.";
                        return False;
                    }
                    if($won - $loss == 1)
                    {
                        $this->message = "Must win by 2 clear points";
                        return False;
                    }
                }
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
        return $this->message;
    }
}

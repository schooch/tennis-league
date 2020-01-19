<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMatchPairs implements Rule
{
    private $message = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($players)
    {
        $this->players = $players;
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
        // Adds the matches that have been played into a list
        // could be ['AA', 'AB', 'BA', 'BB']
        $played = [];
        foreach ($value as $key => $val)
        {
            foreach ($val as $k => $v) {
                if($v['home'] != 0 || $v['away'] != 0)
                {
                    array_push($played, $key);
                    break;
                }
            }
        }
        if($played == [])
        {
            $this->message = 'No scores have been filled in';
            return False;
        }
        //Adds the unique home and away teams to their own lists
        // ['A', 'B'], ['A'] or ['B']
        $home_pairs = [];
        $away_pairs = [];
        foreach ($played as $key => $val)
        {
            if(!in_array($val[0], $home_pairs))
            {
                array_push($home_pairs, $val[0]);
            }
            if(!in_array(substr($val, -1), $away_pairs))
            {
                array_push($away_pairs, substr($val, -1));
            }
        }
        //Checks the teams against the players playing.
        foreach ($home_pairs as $key => $val)
        {
            $char = strtolower($val);
            if (!isset($this->players['home'][$char . '1']))
            {
                $this->message = 'Matches Must have players registered.';
                return False;
            }
        }
        foreach ($away_pairs as $key => $val)
        {
            $char = strtolower($val);
            if (!isset($this->players['away'][$char . '1']))
            {
                $this->message = 'Matches Must have players registered.';
                return False;
            }
        }
        $count = 0;
        foreach ($this->players as $key => $play)
        {
            if(isset($play['a1']))
            {
                $count++;
            }
            if(isset($play['b1']))
            {
                $count++;
            }
        }
        if($count == 4)
        {
            if(count($played) == 1)
            {
                $this->message = 'Minimum of two matches must be played.';
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
        return $this->message;
    }
}

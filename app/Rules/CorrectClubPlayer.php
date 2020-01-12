<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CorrectClubPlayer implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $home = $value['home'];
        $away = $value['away'];
        $teams = DB::table('fixtures')
            ->join('teams as homeT', 'fixtures.homeTeamID', 'homeT.teamID')
            ->join('teams as awayT', 'fixtures.awayTeamID', 'awayT.teamID')
            ->join('clubs as homeC', 'homeT.clubID', 'homeC.clubID')
            ->join('clubs as awayC', 'awayT.clubID', 'awayC.clubID')
            ->where('fixtureID', $this->id)
            ->select('homeC.clubID as homeClubID',
                     'awayC.clubID as awayClubID')
            ->first();
        foreach ($home as $key => $v)
        {
            if(!isset($v))
            {
                continue;
            }
            if (!DB::table('players')->where('clubID', $teams->homeClubID)->where('playerID', $v)->exists())
            {
                return False;
            }
        }
        foreach ($away as $key => $v)
        {
            if(!isset($v))
            {
                continue;
            }
            if (!DB::table('players')->where('clubID', $teams->awayClubID)->where('playerID', $v)->exists())
            {
                return False;
            }
        }
        return True;
    }

    /**
     * Get the validation error message.
     * This shouldn't be reached due to list box, but
     * @return string
     */
    public function message()
    {
        return 'The player must play for the right club';
    }
}

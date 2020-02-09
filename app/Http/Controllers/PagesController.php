<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;
use App\Classes\FixturePlayers;
use App\Classes\Matches;
use DateTime;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    /**
     * querys the database for which leage to get the teams. returns a list of list of DB.
     * @param int $league An int to specify which league it is in from LeagueType
     */
    private function teams($league)
    {
        $headers = array('Team', 'Played', 'Won', 'Drawn', 'Lost', 'Points For', 'Points Against', 'Total Points');
        $clubs = DB::table('clubs')->get();
        $divisions = DB::table('teams')
            ->where('teams.leagueType', $league)
            ->max('division');
        $teams = array();
        for($i = 1; $i <= $divisions; $i++)
        {
            $team = DB::select(DB::raw(config('sql.divisions')), array('div' => $i, 'league' => $league));
            array_push($teams, $team);
        }

        return view('pages.league', ['league' => $league,
                                     'teams' => $teams,
                                     'headers' => $headers]);
    }

    private function queryFixture($id)
    {
        return DB::table('fixtures')
            ->join('venues', 'fixtures.venueID', 'venues.venueID')
            ->join('teams as homeT', 'fixtures.homeTeamID', 'homeT.teamID')
            ->join('teams as awayT', 'fixtures.awayTeamID', 'awayT.teamID')
            ->join('clubs as homeC', 'homeT.clubID', 'homeC.clubID')
            ->join('clubs as awayC', 'awayT.clubID', 'awayC.clubID')
            ->where('fixtureID', $id)
            ->select('homeT.division',
                    'fixtures.weekNum',
                    'venues.venue',
                    'fixtures.MatchDate',
                    'homeT.dayOfWeekOffset as offSet',
                    'homeC.clubName as homeClub',
                    'homeT.teamChar as homeChar',
                    'awayC.clubName as awayClub',
                    'awayT.teamChar as awayChar')
            ->first();
    }

    /**
     * Querys the database and returns a list of divisions which is a list of objects of each team.
     * makes a view to return
     * @return view A view to the mens league with the database queryed
     */
    public function mens()
    {
        $league = (LeagueType::MENS);
        return $this->teams($league);
    }

    /**
     * Querys the database and returns a list of divisions which is a list of objects of each team.
     * makes a view to return
     * @return view A view to the ladies league with the database queryed
     */
    public function ladies()
    {
        $league = LeagueType::LADIES;
        return $this->teams($league);
    }

    public function clubs()
    {
        $clubs = DB::table('clubs')->get('clubName');
        // return $teams;
        return view('pages.clubs', ['clubs' => $clubs]);
    }

}

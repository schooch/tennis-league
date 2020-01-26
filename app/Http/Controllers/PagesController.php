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
    /**
     * Query the database with the teamID, makes the 10 fixtures and
     */
    public function fixtures($club, $team)
    {

        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);
        $fullTeamName = ucwords(strtolower($club)) . ' ' . strtoupper($teamChar) . ' ' . LeagueType::getDescription($league);
        $clubSearch = DB::table('clubs')->where("clubName", $club)->get();
        if (count($clubSearch) != 1)
        {
            return redirect('clubs');
        }

        $id = DB::table('clubs')
            ->join('teams', 'clubs.clubID', '=', 'teams.clubID')
            ->where("clubName", $club)
            ->where("teamChar", $teamChar)
            ->where("leagueType", $league)
            ->value('teams.teamID');

        if ($id == '')
        {
            return redirect($club);
        }

        $fixture = DB::select(DB::raw(config('sql.fixtures')), [$id, $id]);
        $monday = config('controlConsts.start');
        $fixtures = [];
        $count = 0;
        for ($i = 0; $i < 10; $i++)
        {
            if (in_array($monday, config('controlConsts.rest')))
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            if ($count >= count($fixture))
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            elseif ($fixture[$count]->weekNumber != $i+1)
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            else
            {
                $fix = new Fixture($fixture[$count]->fixtureID,
                    $fixture[$count]->weekNumber,
                    $fixture[$count]->home,
                    $fixture[$count]->away,
                    $monday,
                    $fixture[$count]->day);
                array_push($fixtures, $fix);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                $count++;
            }
        }
        return view('pages.fixtures', ['fixtures' => $fixtures,
                                       'team' => $fullTeamName]);
    }

    public function clubs()
    {
        $clubs = DB::table('clubs')->get('clubName');
        // return $teams;
        return view('pages.clubs', ['clubs' => $clubs]);
    }

}

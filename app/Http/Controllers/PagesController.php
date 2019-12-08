<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;

class PagesController extends Controller
{
    public function index() {
        return view('pages.index');
    }


    /**
     * querys the database for which leage to get the teams. returns a list of list of DB.
     * @param int $league An int to specify which league it is in from LeagueType
     */
    private function teams($league) {
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

    /**
     * Querys the database and returns a list of divisions which is a list of objects of each team.
     * makes a view to return
     * @return view A view to the mens league with the database queryed
     */
    public function mens() {
        $league = (LeagueType::MENS);
        return $this->teams($league);
    }

    /**
     * Querys the database and returns a list of divisions which is a list of objects of each team.
     * makes a view to return
     * @return view A view to the ladies league with the database queryed
     */
    public function ladies() {
        $league = LeagueType::LADIES;
        return $this->teams($league);
    }

    /**
     * Query the database with the teamID, makes the 10 fixtures and
     */
    public function fixtures($club, $team) {
        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);

        $fullTeamName = $club . ' ' . $teamChar . ' ' . LeagueType::getDescription($league);

        $id = DB::table('clubs')
            ->join('teams', 'clubs.clubID', '=', 'teams.clubID')
            ->where("clubName", $club)
            ->where("teamChar", $teamChar)
            ->where("leagueType", $league)
            ->value('teams.teamID');

        $result = DB::select(DB::raw(config('sql.fixtures')), [$id, $id]);
        $monday = config('controlConsts.start');
        $fixtures = [];
        foreach ($result as $fixture)
        {
            if (in_array($monday, config('controlConsts.rest')))
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            $fix = new Fixture($fixture->weekNumber,
                     $fixture->home,
                     $fixture->away,
                     $monday,
                     $fixture->day);
            array_push($fixtures, $fix);
            date_add($monday, date_interval_create_from_date_string("1 week"));
        }
        return view('pages.fixtures', ['fixtures' => $fixtures,
                                       'team' => $fullTeamName]);
    }

    private static function rotate($toRotate)
    {
        $height = count($toRotate);
        $width = count($toRotate[0]);
        $result = array();
        for ($i = 0; $i < $width; $i++) {
            $row = array();
            for ($j = 0; $j < $height; $j++) {
                array_push($row, $toRotate[$j][$i]);
            }
            array_push($result, $row);
        }

        return $result;
    }

    public function club($club)
    {
        $result = array();
        $count = array();
        foreach (LeagueType::toArray() as $key => $val)
        {
            $teams = DB::table('clubs')
            ->join('teams', 'clubs.clubID', '=', 'teams.clubID')
            ->where("clubName", $club)
            ->where("leagueType", $val)
            ->select('teamChar', 'leagueType')
            ->get();
            foreach ($teams as $team)
            {
                $team->leagueType = LeagueType::getDescription($team->leagueType);
            }
            if(count($teams) > 0)
            {
                array_push($result, $teams);
            }
            array_push($count, count($teams));
        }

        foreach ($result as $leagueType)
        {
            for ($i = count($leagueType); $i < max($count); $i++)
            {
                $leagueType[$i] = null;
            }
        }
        //return $this::rotate($result);
        return view('pages.club', ['club' => $club,
                                'teams' => $this::rotate($result)]);
    }

    public function clubs()
    {
        $clubs = DB::table('clubs')->get('clubName');
        // return $teams;
        return view('pages.clubs', ['clubs' => $clubs]);
    }

}

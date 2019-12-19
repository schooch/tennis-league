<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;
use App\Classes\FixturePlayers;
use DateTime;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    private static function rotate($toRotate)
    {
        $height = count($toRotate);
        $width = count($toRotate[0]);
        $fixture = array();
        for ($i = 0; $i < $width; $i++) {
            $row = array();
            for ($j = 0; $j < $height; $j++) {
                array_push($row, $toRotate[$j][$i]);
            }
            array_push($fixture, $row);
        }

        return $fixture;
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

    public function fixture($id)
    {
        $players = null;
        $fixture = DB::table('fixtures')
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
        if ($fixture == [])
        {
            return redirect("/");
        }
        if ($fixture->MatchDate == null)
        {
            $date = config('controlConsts.start');
            for ($i = 1; $i < $fixture->weekNum; $i++)
            {
                date_add($date, date_interval_create_from_date_string("1 week"));
                if (in_array($date, config('controlConsts.rest')))
                {
                    date_add($date, date_interval_create_from_date_string("1 week"));
                }
            }
            date_add($date, date_interval_create_from_date_string($fixture->offSet . " days"));
            $fixture->MatchDate = $date->format('d/m/y');
        }
        else
        {
            $date = new DateTime($fixture->MatchDate);
            $fixture->MatchDate = $date->format('d-m-y'); //TODO change this to '/'
            $players = new FixturePlayers($id);
            //return $players->awayB2->playerName. ' ';
        }
        return view('pages.fixture', ['fixture' => $fixture,
                                      'players' => $players
                                    ]);
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
        /*
        foreach ($fixture as $fixture)
        {


        }*/
        return view('pages.fixtures', ['fixtures' => $fixtures,
                                       'team' => $fullTeamName]);
    }

    public function club($club)
    {
        $fixture = array();
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
                array_push($fixture, $teams);
            }
            array_push($count, count($teams));
        }
        if(count($fixture) == 0)
        {
            return redirect('clubs');
        }
        foreach ($fixture as $leagueType)
        {
            for ($i = count($leagueType); $i < max($count); $i++)
            {
                $leagueType[$i] = null;
            }
        }
        //return $this::rotate($fixture);
        return view('pages.club', ['club' => ucwords(strtolower($club)),
                                'teams' => $this::rotate($fixture)]);
    }

    public function clubs()
    {
        $clubs = DB::table('clubs')->get('clubName');
        // return $teams;
        return view('pages.clubs', ['clubs' => $clubs]);
    }

}

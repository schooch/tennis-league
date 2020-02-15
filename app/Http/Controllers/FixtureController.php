<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;
use App\Classes\FixturePlayers;
use App\Classes\Matches;
use App\Rules\UniqPlayers;
use App\Rules\CorrectClubPlayer;
use App\Rules\Pairs;
use App\Rules\EnoughPairs;
use App\Rules\ValidMatchPairs;
use App\Rules\ValidMatchScore;
use DateTime;

class FixtureController extends Controller
{

    public function show($id)
    {
        $players = $matches = null;
        $fixture = $this->queryFixture($id);
        if ($fixture == [])
        {
            return redirect("/");
        }
        //Match hasn't happened
        if ($fixture->matchDate == null)
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
            $fixture->matchDate = $date->format('d/m/y');
        }
        else
        {
            $date = new DateTime($fixture->matchDate);
            $fixture->matchDate = $date->format('d/m/y');
            $players = new FixturePlayers($id);
            $matches = new Matches($id);
            $matches = $matches->getDict();
        }
        $homePlayers = $this->clubPlayers($fixture->homeClubID);

        $awayPlayers = $this->clubPlayers($fixture->awayClubID);
        //players is for the ones who played, home/awayPlayers is for list boxes
        return view('fixtures.show', ['id' => $id,
                                      'fixture' => $fixture,
                                      'players' => $players,
                                      'matches' => $matches,
                                      'homePlayers' => $homePlayers,
                                      'awayPlayers' => $awayPlayers
                                    ]);
    }

    private function clubPlayers($club)
    {
        $result = DB::table('players')
        ->where('clubID', $club)
        ->get();
        //return $result;
        $toReturn = array();
        foreach ($result as $key => $value) {
            $toReturn[$value->playerID] = $value->playerName;
        }
        return $toReturn;
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
                    'fixtures.MatchDate as matchDate',
                    'homeT.dayOfWeekOffset as offSet',
                    'homeC.clubName as homeClub',
                    'homeC.clubID as homeClubID',
                    'homeT.teamChar as homeChar',
                    'awayC.clubName as awayClub',
                    'awayC.clubID as awayClubID',
                    'awayT.teamChar as awayChar')
            ->first();
    }

    public function store(Request $request)
    {
        //return $request->matchDate;
        $this->validate($request, [
            'matchDate' => 'before_or_equal:today',
            'players' => [new UniqPlayers(), new CorrectClubPlayer($request->id), new EnoughPairs, new Pairs],
            'match' => [new ValidMatchPairs($request->players), new ValidMatchScore]
            ]);
        return $request;
    }

}

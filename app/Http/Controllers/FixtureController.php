<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;
use App\Classes\FixturePlayers;
use App\Classes\Matches;
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
            $fixture->MatchDate = $date->format('d/m/y');
            $players = new FixturePlayers($id);
            $matches = new Matches($id);
            $matches = $matches->getDict();
        }
        return view('fixtures.fixture', ['fixture' => $fixture,
                                      'players' => $players,
                                      'matches' => $matches
                                    ]);
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

} 

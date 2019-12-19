<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;

class FixturePlayers{
    public $homeA1;
    public $homeA2;
    public $homeB1;
    public $homeB2;
    public $awayA1;
    public $awayA2;
    public $awayB1;
    public $awayB2;

    public function __construct($id)
    {
        $homeOrAway = "fixtures.homeTeamID";
        $char = 'A_';
        $players = $this->twoPlayers($homeOrAway, $id, $char);
        $this->homeA1 = $players[0];
        $this->homeA2 = $players[1];
        $char = 'B_';
        $players = $this->twoPlayers($homeOrAway, $id, $char);
        $this->homeB1 = $players[0];
        $this->homeB2 = $players[1];

        $homeOrAway = "fixtures.awayTeamID";
        $char = '_A';
        $players = $this->twoPlayers($homeOrAway, $id, $char);
        $this->awayA1 = $players[0];
        $this->awayA2 = $players[1];
        $char = '_B';
        $players = $this->twoPlayers($homeOrAway, $id, $char);
        $this->awayB1 = $players[0];
        $this->awayB2 = $players[1];
    }

    private function twoPlayers($homeOrAway, $id, $char)
    {
        return DB::table('players')
        ->select('players.playerID as ID', 'players.playerName')
            ->join('match_players', 'players.playerID', 'match_players.playerID')
            ->join('matches', 'match_players.matchID', 'matches.matchID')
            ->join('fixtures', 'matches.fixtureID', 'fixtures.fixtureID')
            ->join('teams', $homeOrAway, 'teams.teamID')
            ->where('matches.fixtureID', '=', $id)
            ->where('matches.whoVswho', 'like', $char)
            ->whereColumn('players.clubID', 'teams.clubID')
            ->groupBy('ID', 'players.playerName')
            ->get();
    }
}

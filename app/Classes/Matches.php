<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;

class Matches{
    public $AA;
    public $AB;
    public $BA;
    public $BB;
    public $toPrint; //TOO remove

    public function __construct($id)
    {
        $matchIDs = DB::table('matches')
            ->where('matches.fixtureID', '=', $id)
            ->orderBy('whoVswho', 'asc')
            ->select('matchID', 'whoVswho')
            ->get();
        foreach ($matchIDs as $matchID)
        {
            if($matchID->whoVswho == 'AA')
            {
                $this->AA = DB::table('matches')
                        ->join('sets', 'matches.matchID', '=', 'sets.matchID')
                        ->where('matches.matchID', '=', $matchID->matchID)
                        ->select('whoVswho', 'homeScore', 'awayScore')
                        ->get();
            }
            if($matchID->whoVswho == 'AB')
            {
                $this->AB = DB::table('matches')
                        ->join('sets', 'matches.matchID', '=', 'sets.matchID')
                        ->where('matches.matchID', '=', $matchID->matchID)
                        ->select('homeScore', 'awayScore')
                        ->get();
            }
            if($matchID->whoVswho == 'BA')
            {
                $this->BA = DB::table('matches')
                        ->join('sets', 'matches.matchID', '=', 'sets.matchID')
                        ->where('matches.matchID', '=', $matchID->matchID)
                        ->select('homeScore', 'awayScore')
                        ->get();
            }
            if($matchID->whoVswho == 'BB')
            {
                $this->BB = DB::table('matches')
                        ->join('sets', 'matches.matchID', '=', 'sets.matchID')
                        ->where('matches.matchID', '=', $matchID->matchID)
                        ->select('homeScore', 'awayScore')
                        ->get();
            }
        }
    }

    public function getDict()
    {
        return array('AA' => $this->AA,
                'AB' => $this->AB,
                'BA' => $this->BA,
                'BB' => $this->BB
            );
    }
}
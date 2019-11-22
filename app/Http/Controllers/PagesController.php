<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function index() {
        $title = "Welcome";
        return view('pages.index', compact('title'));
    } 

    protected function teams($league) {
        $clubs = DB::table('clubs')->get();
        $divisions = DB::table('teams')
            ->where('teams.leagueType', $league)
            ->max('division');

        $teams = array();
        for($i = 1; $i <= $divisions; $i++)
        {
            $team = DB::table('teams')
            ->join('clubs', 'teams.clubID', '=', 'clubs.clubID')
            ->select('clubs.clubName', 'teams.teamChar')
            ->where('teams.leagueType', $league)
            ->where('teams.division', $i)
            ->get();
            array_push($teams, $team);
        }

        return view('pages.league', ['league' => $league, 'teams' => $teams]);
    }

    public function mens() {
        $league = (LeagueType::MENS);
        return $this->teams($league);
    } 

    public function ladies() {
        $league = LeagueType::LADIES;
        return $this->teams($league);
    } 
}

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
        $teams = DB::table('teams')
            ->join('clubs', 'teams.clubID', '=', 'clubs.clubID')
            ->select('clubs.clubName', 'teams.teamChar')
            ->where('teams.leagueType', $league)
            ->get();
        return view('pages.league', ['league' => $league, 'clubs' => $teams]);
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

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

    protected function leagueDB($league) {
        $clubs = DB::table('clubs')->get();
        return view('pages.league', ['league' => $league, 'clubs' => $clubs]);
    }

    public function mens() {
        $league = LeagueType::MENS;
        return $this->leagueDB($league);
    } 

    public function ladies() {
        $league = LeagueType::LADIES;
        return $this->leagueDB($league);
    } 


}

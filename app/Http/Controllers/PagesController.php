<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;

class PagesController extends Controller
{
    public function index() {
        $title = "Welcome";
        return view('pages.index', compact('title'));
    } 

    public function mens() {
        $league = LeagueType::MENS;
        return view('pages.league')->with('league', $league);
    } 

    public function ladies() {
        $league = LeagueType::LADIES;
        return view('pages.league')->with('league', $league);
    } 


}

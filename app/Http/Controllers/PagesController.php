<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;
use App\Classes\Fixture;
use App\Classes\FixturePlayers;
use App\Classes\Matches;
use DateTime;

class PagesController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }
}

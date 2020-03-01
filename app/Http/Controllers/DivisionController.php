<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\LeagueType;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($league)
    {
        $league = LeagueType::getValueFromString($league);
        $headers = array('Team', 'Played', 'Won', 'Drawn', 'Lost', 'Points For', 'Points Against', 'Total Points');
        $divisions = DB::table('teams')
            ->where('teams.leagueType', $league)
            ->max('division');
        $teams = array();
        for($i = 1; $i <= $divisions; $i++)
        {
            $team = DB::select(DB::raw(config('sql.divisions')), array('div' => $i, 'league' => $league));
            array_push($teams, $team);
        }
        return view('divisions.index', [
            'league' => LeagueType::getLowerDescription($league),
            'teams' => $teams,
            'headers' => $headers
            ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($div)
    {
        $league = substr($div, 0, -1);
        $div = intval(substr($div, -1));
        $league = LeagueType::getValueFromString($league);
        // return $league;
        $headers = array('Team', 'Played', 'Won', 'Drawn', 'Lost', 'Points For', 'Points Against', 'Total Points');
        $teams = DB::select(DB::raw(config('sql.divisions')), array('div' => $div, 'league' => $league));
        return view('divisions.show', [
            'league' => LeagueType::getLowerDescription($league),
            'teams' => $teams,
            'headers' => $headers,
            'div' => $div
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}

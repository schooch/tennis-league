<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\LeagueType;

class ClubController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    private static function rotate($toRotate)
    {
        $height = count($toRotate);
        $width = count($toRotate[0]);
        $fixture = array();
        for ($i = 0; $i < $width; $i++) {
            $row = array();
            for ($j = 0; $j < $height; $j++) {
                array_push($row, $toRotate[$j][$i]);
            }
            array_push($fixture, $row);
        }

        return $fixture;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clubs = DB::table('clubs')->get('clubName');
        return view('clubs.index', ['clubs' => $clubs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->userType < 2)
        {
            return redirect('/clubs')->with('error', 'Unauthorized Page');
        }
        return view('clubs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->userType < 2)
        {
            return redirect('/clubs')->with('error', 'Unauthorized Page');
        }
        DB::table('clubs')->insert(
            ['clubName' => $request->clubName]
        );
        return redirect('/clubs')->with('success', 'Club Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blockUpperId = strtoupper(str_replace(' ', '', $id));
        $teamNames = DB::table('clubs')->select('clubID', 'clubName')->get();
        $clubName = '';
        $idNames = [];
        for ($i=0; $i < count($teamNames); $i++)
        {
            $idNames[$teamNames[$i]->clubID] = strtoupper(str_replace(' ', '', $teamNames[$i]->clubName));
        }
        if (in_array($blockUpperId, $idNames))
        {
            $id = array_search($blockUpperId, $idNames);
            //Needs a [0] as pluck and first don't work together.
            $clubName = DB::table('clubs')
                ->where('clubID', $id)
                ->pluck('clubName')[0];
        }
        else
        {
            return redirect('clubs');
        }

        $leagueTeams = array();
        $count = array();
        foreach (LeagueType::toArray() as $key => $val)
        {
            $teams = DB::table('teams')
            ->where("clubID", $id)
            ->where("leagueType", $val)
            ->select('teamChar', 'leagueType')
            ->orderBy('teamChar')
            ->get();
            foreach ($teams as $team)
            {
                $team->leagueType = LeagueType::getDescription($team->leagueType);
            }
            if(count($teams) > 0)
            {
                array_push($leagueTeams, $teams);
            }
            array_push($count, count($teams));
        }

        foreach ($leagueTeams as $leagueType)
        {
            for ($i = count($leagueType); $i < max($count); $i++)
            {
                $leagueType[$i] = null;
            }
        }
        return view('clubs.show', ['club' => ucwords(strtolower($clubName)),
                                    'teams' => $this::rotate($leagueTeams),
                                    'leagues' => LeagueType::getKeys()
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
        if(auth()->user()->userType < 2)
        {
            return redirect('/clubs')->with('error', 'Unauthorized Page');
        }
        return view('clubs.edit', ['name' => $id]);
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
        if(auth()->user()->userType < 2)
        {
            return redirect('/clubs')->with('error', 'Unauthorized Page');
        }
        $this->validate($request, [
            'clubName' => 'required'
        ]);
        DB::table('clubs')
        ->where('clubName', $id)
        ->update(['clubName' => $request->clubName]);
        return redirect('/clubs')->with('success', 'Club Name Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->userType < 2)
        {
            return redirect('/clubs')->with('error', 'Unauthorized Page');
        }
        $teams =  DB::table('clubs')
        ->join('teams', 'clubs.clubID', 'teams.clubID')
        ->where('clubs.clubName', $id)->count();
        if ($teams > 0)
        {
            return redirect($id)->with('error', 'Can\'t delete a club with teams.');
        }
        DB::table('clubs')
        ->where('clubName', $id)->delete();
        return redirect('/clubs')->with('success', 'Club Deleted');
    }
}
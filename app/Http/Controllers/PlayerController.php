<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = DB::table('players')->select('playerName', 'playerID')->get();
        return view('players.index', ['players' => $players]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result = DB::table('clubs')->select('clubID', 'clubName')->get();
        $clubs = [];
        foreach ($result as $value) {
            $clubs[$value->clubID] = $value->clubName;
        }
        // return $clubs;
        return view('players.create', ['clubs' => $clubs]);
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
        $this->validate($request, [
            'playerName' => 'required',
            'club' => 'required',
            ]);
        DB::table('players')->insert(
            ['playerName' => $request->playerName,
             'clubID' => $request->club
            ]
        );
        return redirect('/players')->with('success', 'Player Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $player = DB::table('players')
        ->join('clubs', 'players.clubID', 'clubs.clubID')
        ->where('playerID', $id)
        ->select('playerName', 'clubs.clubName')
        ->first();
        return view('players.show', [
            'playerName' => $player->playerName,
            'player' => $id,
            'club' => $player->clubName
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
        // At the moment there is no need to edit a player
        return redirect('players/' . $id);
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
        // At the moment there is no need to edit a player
        return redirect('players/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // At the moment there is no need to edit a player
        return redirect('players/' . $id);
    }
}

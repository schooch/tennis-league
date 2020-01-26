<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('clubs.show', ['name' => $id]);
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
        DB::table('clubs')
        ->where('clubName', $id)->delete();
        return redirect('/clubs')->with('success', 'Club Deleted');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\LeagueType;
use App\Enums\DayOffset;
use App\Classes\Fixture;

class TeamController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
        return view('teams.create', [
            'club' => $club,
            'leagues' => LeagueType::getTitleKeys(),
            'dayOffSet' => DayOffset::getTitleKeys()
            ]);
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
            'dayOffSet' => 'required',
            'leagueType' => 'required',
            'club' => 'required'
        ]);

        $clubTeams = DB::table('clubs')
        ->join('teams', 'clubs.clubID', 'teams.clubID')
        ->where('clubs.clubName', $request->club)
        ->where('teams.leagueType', $request->leagueType)
        ->select('teams.teamChar', 'clubs.clubID')
        ->get();

        $alphas = range('A', 'Z');
        for ($i=0; $i < count($clubTeams); $i++) {
            if ($clubTeams[$i]->teamChar != $alphas[$i])
            {
                return redirect($request->club)->with('error', 'There is a problem with the existing teams, please contact the administrator.');
            }
        }

        DB::table('teams')->insert([
            'teamChar' => $alphas[count($clubTeams)],
            'clubID' => $clubTeams[0]->clubID,
            'dayOfWeekOffset' => $request->dayOffSet,
            'leagueType' => $request->leagueType
            ]
        );

        return redirect($request->club)->with('success', 'Team Added');
    }

    private function showFixture($id, $fullTeamName)
    {

        $fixture = DB::select(DB::raw(config('sql.fixtures')), [$id, $id]);
        $monday = config('controlConsts.start');
        $fixtures = [];
        $count = 0;
        for ($i = 0; $i < 10; $i++)
        {
            if (in_array($monday, config('controlConsts.rest')))
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            if ($count >= count($fixture))
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            elseif ($fixture[$count]->weekNumber != $i+1)
            {
                $fix = Fixture::restWeek($monday);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                array_push($fixtures, $fix);
            }
            else
            {
                $fix = new Fixture($fixture[$count]->fixtureID,
                    $fixture[$count]->weekNumber,
                    $fixture[$count]->home,
                    $fixture[$count]->away,
                    $monday,
                    $fixture[$count]->day);
                array_push($fixtures, $fix);
                date_add($monday, date_interval_create_from_date_string("1 week"));
                $count++;
            }
        }
        return view('fixtures.index', ['fixtures' => $fixtures,
                                       'team' => $fullTeamName]
                                    );
    }

    private function showNewTeam($club, $team, $fullTeamName, $league, $teamChar)
    {
        $current = DB::table('teams')
        ->join('clubs', 'teams.clubID', 'clubs.clubID')
        ->where('clubName', $club)
        ->where('leagueType', $league)
        ->where('teamChar', $teamChar)
        ->select('dayOfWeekOffset', 'leagueType')
        ->first();
        return view('teams.show', ['club' => $club,
                                   'team' => $team,
                                   'teamFull' => $fullTeamName,
                                   'leagueType' => LeagueType::getDescription($current->leagueType),
                                   'day' => $current->dayOfWeekOffset]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($club, $team)
    {
        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);
        $fullTeamName = ucwords(strtolower($club)) . ' ' . strtoupper($teamChar) . ' ' . LeagueType::getDescription($league);

        $clubSearch = DB::table('clubs')->where("clubName", $club)->get();
        if (count($clubSearch) != 1)
        {
            return redirect('clubs');
        }

        $id = DB::table('clubs')
            ->join('teams', 'clubs.clubID', '=', 'teams.clubID')
            ->where("clubName", $club)
            ->where("teamChar", $teamChar)
            ->where("leagueType", $league)
            ->value('teams.teamID');

        if ($id == '')
        {
            return redirect($club);
        }
        $codeNumber = DB::table('clubs')
        ->join('teams', 'clubs.clubID', '=', 'teams.clubID')
        ->where("clubName", $club)
        ->where("teamChar", $teamChar)
        ->where("leagueType", $league)
        ->value('teams.codeNumber');

        // If a team isn't part of a division (a new team) it will go to less page.
        if ($codeNumber == '')
        {
            return $this->showNewTeam($club, $team, $fullTeamName, $league, $teamChar);
        }
        return $this->showFixture($id, $fullTeamName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($club, $team)
    {
        if(auth()->user()->userType < 2)
        {
            return redirect($club)->with('error', 'Unauthorized Page');
        }
        if(config('controlConsts.locked'))
        {
            return redirect($club)->with('error', 'League locked.');
        }

        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);

        $current = DB::table('teams')
        ->join('clubs', 'teams.clubID', 'clubs.clubID')
        ->where('clubName', $club)
        ->where('leagueType', $league)
        ->where('teamChar', $teamChar)
        ->select('dayOfWeekOffset', 'leagueType')
        ->first();
        return view('teams.edit', [
            'club' => $club,
            'team' => $team,
            'leagues' => LeagueType::getTitleKeys(),
            'dayOffSet' => DayOffset::getTitleKeys(),
            'currentLeague' => $current->leagueType,
            'currentDay' => $current->dayOfWeekOffset
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $club, $team)
    {
        if(auth()->user()->userType < 2)
        {
            return redirect($club)->with('error', 'Unauthorized Page');
        }
        if(config('controlConsts.locked'))
        {
            return redirect($club)->with('error', 'League locked.');
        }

        $this->validate($request, [
            'dayOffSet' => 'required'
        ]);

        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);

        DB::table('teams')
        ->join('clubs', 'teams.clubID', 'clubs.clubID')
        ->where('clubName', $club)
        ->where('leagueType', $request->leagueType)
        ->where('teamChar', $teamChar)
        ->update(['dayOfWeekOffset' => $request->dayOffSet]);
        return redirect($club)->with('success', 'Team Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($club, $team)
    {
        if(auth()->user()->userType < 2)
        {
            return redirect($club)->with('error', 'Unauthorized Page');
        }
        $teams =  DB::table('clubs')
        ->join('teams', 'clubs.clubID', 'teams.clubID')
        ->where('clubs.clubName', $club)->count();
        if ($teams == 0)
        {
            return redirect($club)->with('error', 'There are no teams.');
        }

        $teamChar = substr($team, -1);
        $leagueStr = strtoupper(substr($team, 0, -1));
        $league = LeagueType::getValueFromString($leagueStr);

        $clubTeams = DB::table('clubs')
        ->join('teams', 'clubs.clubID', 'teams.clubID')
        ->where('clubs.clubName', $club)
        ->where('teams.leagueType', $league)
        ->select('teams.teamChar', 'clubs.clubID')
        ->get();

        $alphas = range('A', 'Z');
        for ($i=0; $i < count($clubTeams); $i++) {
            if ($clubTeams[$i]->teamChar != $alphas[$i])
            {
                return redirect($club)->with('error', 'There is a problem with the existing teams, please contact the administrator.');
            }
        }
        if($alphas[count($clubTeams)-1] != $teamChar)
        {
            return redirect($club)->with('error', 'Can only delete the last team.');
        }
        DB::table('teams')
        ->join('clubs', 'teams.clubID', 'clubs.clubID')
        ->where('clubName', $club)
        ->where('leagueType', $league)
        ->where('teamChar', $teamChar)
        ->delete();

        return redirect($club)->with('success', 'Club Deleted');
    }
}

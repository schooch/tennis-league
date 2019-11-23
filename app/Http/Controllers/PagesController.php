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

    private function played()
    {
        return "(
            SELECT count(*) 
            FROM fixtures 
            WHERE matchDate 
            IS NOT NULL 
            AND (homeTeamID = teams.teamID
            OR awayTeamID = teams.teamID)) 
            AS pld";
    }

    private function pointsTally($for)
    {
        return "(SELECT count(*) 
        FROM fixtures
        INNER JOIN matches
        ON matches.fixtureID = fixtures.fixtureID
        INNER JOIN sets
        ON sets.matchID = matches.matchID
        WHERE (homeScore " . ($for ? ">" : "<") . " awayScore AND homeTeamID = teamID) 
        OR (homeScore " . ($for ? "<" : ">") . " awayScore AND awayTeamID = teamID)) 
        AS ";
    }

    private function pointsForOrAgainst($for)
    {
         return $this->pointsTally($for) . ($for ? "pointsFor" : "pointsAgainst");
    }

    private function totalPoints()
    {
        return "(SELECT COALESCE(sum(homeScoreChange), 0) FROM fixtures
                WHERE homeTeamID = teamID) + 
                (SELECT COALESCE(sum(awayScoreChange), 0) FROM fixtures
                WHERE awayTeamID = teamID) +"
                . $this->pointsTally(True)
                . "totalPoints";
    }

    protected function teams($league) {
        $headers = array('Club', 'Team', 'Played', 'Points For', 'Points Against', 'Total Points');
        $clubs = DB::table('clubs')->get();
        $divisions = DB::table('teams')
            ->where('teams.leagueType', $league)
            ->max('division');

        $teams = array();
        for($i = 1; $i <= $divisions; $i++)
        {
            $team = DB::table('teams')
            ->join('clubs', 'teams.clubID', '=', 'clubs.clubID')
            ->select('clubs.clubName',
                     'teams.teamChar',
                     DB::raw($this->played()),
                     DB::raw($this->pointsForOrAgainst(True)),
                     DB::raw($this->pointsForOrAgainst(False)),
                     DB::raw($this->totalPoints())
                     )

            ->where('teams.leagueType', $league)
            ->where('teams.division', $i)
            ->get();
            array_push($teams, $team);
        }

        return view('pages.league', ['league' => $league, 
                                     'teams' => $teams, 
                                     'headers' => $headers]);
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

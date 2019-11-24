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

    /**
     * @return string returns the full query for getting the teams stats.
     */
    protected function fullRawQuery(){
        return "SELECT clubName, 
        teamChar,
        (SELECT count(*) 
            FROM fixtures 
            WHERE matchDate 
            IS NOT NULL 
            AND (homeTeamID = teams.teamID
            OR awayTeamID = teams.teamID)) 
        AS pld,
        (SELECT count(*) 
            FROM fixtures
            INNER JOIN matches
            ON matches.fixtureID = fixtures.fixtureID
            INNER JOIN sets
            ON sets.matchID = matches.matchID
            WHERE (homeScore > awayScore AND homeTeamID = teams.teamID) 
            OR (homeScore < awayScore AND awayTeamID = teams.teamID)) 
        AS pointsFor,
        (SELECT count(*)
            FROM fixtures
            INNER JOIN matches
            ON matches.fixtureID = fixtures.fixtureID
            INNER JOIN sets
            ON sets.matchID = matches.matchID
            WHERE (homeScore < awayScore AND homeTeamID = teams.teamID) 
            OR (homeScore > awayScore AND awayTeamID = teams.teamID)) 
        AS pointsAgainst,
        (SELECT COALESCE(sum(homeScoreChange), 0) FROM fixtures
            WHERE homeTeamID = teams.teamID) + 
            (SELECT COALESCE(sum(awayScoreChange), 0) FROM fixtures
            WHERE awayTeamID = teams.teamID) + 
            (SELECT count(*)
            FROM fixtures
            INNER JOIN matches
            ON matches.fixtureID = fixtures.fixtureID
            INNER JOIN sets
            ON sets.matchID = matches.matchID
            WHERE (homeScore > awayScore AND homeTeamID = teams.teamID) 
            OR (homeScore < awayScore AND awayTeamID = teams.teamID)) 
        AS totalPoints,
        teams.teamID,
        COALESCE(subTable.w, 0) as won,
        COALESCE(subTable.d, 0) as drawn,
        COALESCE(subTable.l, 0) as lost       
        FROM teams
        INNER JOIN clubs
        ON clubs.clubID = teams.clubID
        LEFT JOIN (SELECT 
            teamID
            , sum(fixWin) as w
            , sum(fixDraw) as d
            , sum(fixLoss) as l
            FROM
                (SELECT 
                    teamID as teamid
                    , case when win > loss THEN 1 else 0 end fixWin
                    , case when win = loss THEN 1 else 0 end fixDraw
                    , case when win < loss THEN 1 else 0 end fixLoss
                FROM
                    (SELECT 
                        fixtureID
                        , teamID
                        , sum(setWin) AS win
                        , sum(setLoss) AS loss
                    FROM
                        (SELECT
                            f.fixtureID
                            , f.homeTeamID teamID
                            , case when homeScore > awayScore THEN 1 else 0 end setWin
                            , case when homeScore < awayScore THEN 1 else 0 end setLoss
                        FROM
                            fixtures f
                            INNER JOIN matches m
                                ON f.fixtureID = m.fixtureID
                            INNER JOIN sets s
                                ON m.matchID = s.matchID) as homeWin
                        group by fixtureID       
                    union
                    SELECT fixtureID, teamID, sum(setWin), sum(setLoss) FROM
                        (SELECT
                            f.fixtureID
                            , f.awayTeamID teamID
                            , case when homeScore < awayScore THEN 1 else 0 end setWin
                            , case when homeScore > awayScore THEN 1 else 0 end setLoss
                        FROM
                            fixtures f
                            INNER JOIN matches m
                                ON f.fixtureID = m.fixtureID
                            INNER JOIN sets s
                                ON m.matchID = s.matchID) as awayWin
                        group by fixtureID) AS TBL) as tbl2
            group by teamID) as subTable
            ON teams.teamID = subTable.teamID
            WHERE division = :div
            AND leagueType = :league
            ORDER BY totalPoints DESC";
    }


    /**
     * querys the database for which leage to get the teams. returns a list of list of DB.
     * @param int $league An int to specify which league it is in from LeagueType
     */
    private function teams($league) {
        $headers = array('Team', 'Played', 'Won', 'Drawn', 'Lost', 'Points For', 'Points Against', 'Total Points');
        $clubs = DB::table('clubs')->get();
        $divisions = DB::table('teams')
            ->where('teams.leagueType', $league)
            ->max('division');

        $teams = array();
        for($i = 1; $i <= $divisions; $i++)
        {
            $team = DB::select(DB::raw($this->fullRawQuery()), array('div' => $i, 'league' => $league));
            array_push($teams, $team);
        }

        return view('pages.league', ['league' => $league,
                                     'teams' => $teams,
                                     'headers' => $headers]);
    }

    /**
     * querys the database and returns a list of divisions which is a list of objects of each team.
     * @return [[DB]]
     */
    public function mens() {
        $league = (LeagueType::MENS);
        return $this->teams($league);
    }

    /**
     * querys the database and returns a list of divisions which is a list of objects of each team.
     * @return [[DB]]
     */
    public function ladies() {
        $league = LeagueType::LADIES;
        return $this->teams($league);
    }
}

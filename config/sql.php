<?php

return [

    'divisions' => "SELECT 
    CONCAT(clubs.clubName, ' ', teams.teamChar) as 'fullName',
    (SELECT count(*)
     FROM fixtures
     WHERE matchDate
     IS NOT NULL
     AND (homeTeamID = teams.teamID
          OR awayTeamID = teams.teamID))
    AS pld,
    COALESCE(subTable.w, 0) as won,
    COALESCE(subTable.d, 0) as drawn,
    COALESCE(subTable.l, 0) as lost,
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
    clubName,
    teamChar
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
        WHERE division = 1
        AND leagueType = 0
        ORDER BY totalPoints DESC",


    'fixtures' => "SELECT
    fixtures.weekNum as 'weekNumber',
    CONCAT(homeClub.clubName, ' ', homeTeam.teamChar) as 'home',
    CONCAT(awayClub.clubName, ' ', awayTeam.teamChar) as 'away',
    homeTeam.dayOfWeekOffset as 'day',
    fixtures.fixtureID

    FROM fixtures
    INNER JOIN teams AS homeTeam
    ON fixtures.homeTeamID = homeTeam.teamID
    INNER JOIN teams AS awayTeam
    ON fixtures.awayTeamID = awayTeam.teamID

                    /*Clubs*/
    INNER JOIN clubs AS homeClub
    ON homeTeam.clubID = homeClub.clubID
    INNER JOIN clubs AS awayClub
    ON awayTeam.clubID = awayClub.clubID
    WHERE awayTeamID = ?
    OR
    homeTeamID = ?
    ORDER BY weekNum;",

];
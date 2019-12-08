<?php
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{LeagueType::getDescription($league)}}</h1>
    <p>This is the {{LeagueType::getLowerDescription($league)}} league.</p>
</div>
<div class="container">
    <table class="table table-hover">
        @foreach ($teams as $division)
            <thead class="thead-dark">
                <tr>
                        <th scope="col">#</th>
                    @foreach ($headers as $head)
                        <th scope="col">{{$head}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($division as $team)
                <tr class="table-row">
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$loop->iteration}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->clubName}} {{$team->teamChar}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->pld}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->won}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->drawn}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->lost}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->pointsFor}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->pointsAgainst}}</a></td>
                        <td><a href="/{{$team->clubName}}/{{(LeagueType::getLowerDescription($league))}}{{$team->teamChar}}">{{$team->totalPoints}}</a></td>
                </tr>
                @endforeach
            </tbody>
        @endforeach
    </table>
</div>
@endsection

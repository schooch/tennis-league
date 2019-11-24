<?php
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<h1>{{LeagueType::getDescription($league)}}</h1>
<p>This is the {{(LeagueType::getLowerDescription($league))}} league.</p>
<div class="container">
    <table class="table">
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
                    <tr scope="row">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$team->clubName}} {{$team->teamChar}}</td>
                        <td>{{$team->pld}}</td>
                        <td>{{$team->won}}</td>
                        <td>{{$team->drawn}}</td>
                        <td>{{$team->lost}}</td>
                        <td>{{$team->pointsFor}}</td>
                        <td>{{$team->pointsAgainst}}</td>
                        <td>{{$team->totalPoints}}</td>
                    </tr>
                @endforeach
            </tbody>
        @endforeach
    </table>
</div>
@endsection

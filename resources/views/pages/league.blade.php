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
                    @foreach ($headers as $head)
                        <th scope="col">{{$head}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($division as $team)
                    <tr scope="row">
                        <td>{{$team->clubName}}</td>
                        <td>{{$team->teamChar}}</td>
                        <td>{{$team->pld}}</td>
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

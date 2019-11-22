<?php 
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<h1>{{LeagueType::getDescription($league)}}</h1>
<p>This is the {{(LeagueType::getLowerDescription($league))}} league.</p>
<ul>
    <div class="container">
        @foreach ($teams as $division)
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <td>Club</td>
                    <td>Team</td>
                </thead>
                <tbody>
            @foreach ($division as $team)
                <tr>
                    <td>{{$team->clubName}}</td><td>{{$team->teamChar}}</td>
                </tr>
            @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</ul>
@endsection

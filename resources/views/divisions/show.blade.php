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
            <thead class="thead-dark">
                <tr>
                    <th colspan=9 scope=9>
                        Division {{$div}}
                    </th>
                </tr>
                <tr>
                    <th scope="col">Position</th>
                    @foreach ($headers as $head)
                        <th scope="col">{{$head}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($teams as $team)
                <tr class="table-row">
                        <td><a href="/{{$team->clubName}}/{{$league}}{{$team->teamChar}}">{{$loop->iteration}}</a></td>
                        @foreach ($team as $item)
                            @if ($loop->iteration > 8)
                                @break
                            @endif
                            <td><a href="/{{$team->clubName}}/{{$league}}{{$team->teamChar}}">{{$item}}</a></td>
                        @endforeach
                </tr>
                @endforeach
            </tbody>
    </table>
</div>
@endsection

<?php 
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<h1>{{LeagueType::getDescription($league)}}</h1>
<p>This is the {{(LeagueType::getLowerDescription($league))}} league.</p>
<ul>
    @foreach ($clubs as $club)
        <li>{{$club->clubName}} {{$club->teamChar}}</li>
    @endforeach
</ul>
@endsection

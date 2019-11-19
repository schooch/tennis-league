<?php 
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<h1>{{LeagueType::getDescription($league)}}</h1>
<p>This is the {{strtolower(LeagueType::getDescription($league))}} league.</p>
<ul>
    @foreach ($clubs as $club)
        <li>{{$club->clubName}}</li>
    @endforeach
</ul>
@endsection

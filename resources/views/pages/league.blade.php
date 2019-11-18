<?php 
use App\Enums\LeagueType;
?>

@extends('layouts.app')
@section('content')
<h1>{{LeagueType::getDescription($league)}}</h1>
<p>This is the {{LeagueType::getDescription($league)}} league.</p>
@endsection

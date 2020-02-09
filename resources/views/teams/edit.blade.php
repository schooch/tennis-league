@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Team</h1>
</div>
<div class="container">
    {!!Form::open(['action' => ['TeamController@update', $club, $team], 'method' => 'PUT', 'class' => 'pull-right'])!!}
    {{Form::select('leagueType', $leagues, $currentLeague, ['disabled'])}}
    {{Form::select('dayOffSet', $dayOffSet, $currentDay)}}
    {{Form::hidden('club', $club)}}
    {{Form::submit('Update', ['class' => 'btn btn-primary'])}}
    {!!Form::close()!!}
</div>
@endsection
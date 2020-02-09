@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Team</h1>
</div>
<div class="container">
    {!!Form::open(['action' => ['TeamController@store', $club], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::select('leagueType', $leagues)}}
        {{Form::select('dayOffSet', $dayOffSet)}}
        {{Form::hidden('club', $club)}}
        {{Form::submit('Store', ['class' => 'btn btn-primary'])}}
    {!!Form::close()!!}
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$teamFull}}</h1>
</div>
<div class="container">
    <ul class="list-group">
        <li class="list-group-item">League: {{$leagueType}}</li>
        <li class="list-group-item">Day of the week: {{$day}}</li>
    </ul>

    @if (!Auth::guest() && Auth::user()->userType > 1)
        {!!Form::open(['action' => ['TeamController@destroy', $club, $team], 'method' => 'DELETE', 'class' => 'pull-right'])!!}
            {{Form::submit('Delete Team', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    @endif
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Player</h1>
</div>
<div class="container">
    {!!Form::open(['action' => ['PlayerController@store'], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::label('playerName', 'Player\' Name:')}}
        {{Form::text('playerName')}}<br>
        {{Form::label('club', 'Club:')}}
        {{Form::select('club', $clubs)}}
        {{Form::submit('Store', ['class' => 'btn-primary'])}}
    {!!Form::close()!!}
</div>
@endsection
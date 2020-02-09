@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Players</h1>
</div>
<div class="container">
    @if (!Auth::guest() && Auth::user()->userType > 1)
            <a href="/players/create" class="btn btn-default">Add Player</a>
    @endif
    <ul class="list-group list-group-flush">
        @foreach ($players as $player)
            <a href="/players/{{$player->playerID}}" class="list-group-item list-group-item-action">
                {{$player->playerName}}
            </a>
        @endforeach
    </ul>
</div>
@endsection
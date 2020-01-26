@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Clubs</h1>
</div>
<div class="container">
    @if (!Auth::guest() && Auth::user()->userType > 1)
            <a href="/clubs/create" class="btn btn-default">Add Club</a>
    @endif
    <ul class="list-group list-group-flush">
        @foreach ($clubs as $club)
            <a href="/clubs/{{$club->clubName}}" class="list-group-item list-group-item-action">
                {{$club->clubName}}
            </a>
        @endforeach
    </ul>
</div>
@endsection
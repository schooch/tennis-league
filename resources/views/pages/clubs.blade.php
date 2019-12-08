@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Clubs</h1>
</div>
<div class="container">
    <ul class="list-group list-group-flush">
        @foreach ($clubs as $club)
        <a href="/{{$club->clubName}}" class="list-group-item list-group-item-action">
                {{$club->clubName}}
              </a>
        @endforeach
    </ul>
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$name}}</h1>
</div>
<div class="container">

    @if (!Auth::guest() && Auth::user()->userType > 1)
    <a href="/clubs/{{$name}}/edit" class="btn btn-default">Edit</a>
    {!!Form::open(['action' => ['ClubController@destroy', $name], 'method' => 'DELETE', 'class' => 'pull-right'])!!}
        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
    {!!Form::close()!!}
    @endif
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Club</h1>
</div>
<div class="container">
    {!!Form::open(['action' => ['ClubController@store'], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::text('clubName')}}
        {{Form::submit('Store', ['class' => 'btn'])}}
    {!!Form::close()!!}
</div>
@endsection
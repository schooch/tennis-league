@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Club</h1>
</div>
<div class="container">
    {!!Form::open(['action' => ['ClubController@update', $name], 'method' => 'PUT', 'class' => 'pull-right'])!!}
        {{Form::text('clubName', $name)}}
        {{Form::submit('Update', ['class' => 'btn'])}}
    {!!Form::close()!!}
</div>
@endsection
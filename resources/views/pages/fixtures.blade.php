@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$team}}</h1>
</div>
<div class="container">
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Week Number</th>
                <th scope="col">Home Team</th>
                <th scope="col">Away Team</th>
                <th scope="col">Monday</th>
                <th scope="col">Day of Week</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fixtures as $fixture)
                <tr scope="row">
                    <td scope="col"><a href="/fixture/{{$fixture->id}}">{{$fixture->week}}</a></td>
                    <td scope="col"><a href="/fixture/{{$fixture->id}}">{{$fixture->home}}</a></td>
                    <td scope="col"><a href="/fixture/{{$fixture->id}}">{{$fixture->away}}</a></td>
                    <td scope="col"><a href="/fixture/{{$fixture->id}}">{{$fixture->monday}}</a></td>
                    <td scope="col"><a href="/fixture/{{$fixture->id}}">{{$fixture->day}}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
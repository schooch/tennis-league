@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$team}}</h1>
</div>
<div class="container">
    <table class="table">
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
                    <td scope="col">{{$fixture->week}}</td>
                    <td scope="col">{{$fixture->home}}</td>
                    <td scope="col">{{$fixture->away}}</td>
                    <td scope="col">{{$fixture->monday}}</td>
                    <td scope="col">{{$fixture->day}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.app')
@section('content')
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
            @foreach ($team as $match)
                <tr scope="row">
                    <td scope="col">{{$match->week}}</td>
                    <td scope="col">{{$match->home}}</td>
                    <td scope="col">{{$match->away}}</td>
                    <td scope="col">{{$match->monday}}</td>
                    <td scope="col">{{$match->day}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
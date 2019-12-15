@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$result->homeClub}} {{$result->homeChar}} vs. {{$result->awayClub}} {{$result->awayChar}}</h1>
</div>
<div class="container">
    <table class="table table-hover">
        <tbody>
            <tr scope="row">
                <td align="right"><div class="container">Home Team:</div></td>
                <td><div class="container">{{$result->homeClub}} {{$result->homeChar}}</div></td>
            </tr>
            <tr scope="row">
                <td align="right"><div class="container">Away Team:</div></td>
                <td><div class="container">{{$result->awayClub}} {{$result->awayChar}}</div></td>
            </tr>
            <tr scope="row">
                <td align="right"><div class="container">Venue:</div></td>
                <td><div class="container">{{$result->venue}}</div></td>
            </tr>
            <tr scope="row">
                <td align="right"><div class="container">Division:</div></td>
                <td><div class="container">{{$result->division}}</div></td>
            </tr>
            <tr scope="row">
                <td align="right"><div class="container">Week Number:</div></td>
                <td><div class="container">{{$result->weekNum}}</div></td>
            </tr>
            <tr scope="row">
                <td align="right"><div class="container">Match Date:</div></td>
                <td><div class="container">
                    @isset ($result->MatchDate)
                        {{$result->MatchDate}}
                    @else
                        Empty
                    @endisset
                </div></td>
            </tr>


        </tbody>
    </table>
</div>
@endsection
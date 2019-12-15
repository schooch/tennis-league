@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$result->homeClub}} {{$result->homeChar}} vs. {{$result->awayClub}} {{$result->awayChar}}</h1>
</div>
<div class="container">
    <table class="table table-hover">
        <tbody>
            <tr scope="row">
                <td align="right">Home Team:</td>
                <td>{{$result->homeClub}} {{$result->homeChar}}</td>
            </tr>
            <tr scope="row">
                <td align="right">Away Team:</td>
                <td>{{$result->awayClub}} {{$result->awayChar}}</td>
            </tr>
            <tr scope="row">
                <td align="right">Venue:</td>
                <td>{{$result->venue}}</td>
            </tr>
            <tr scope="row">
                <td align="right">Division:</td>
                <td>{{$result->division}}</td>
            </tr>
            <tr scope="row">
                <td align="right">Week Number:</td>
                <td>{{$result->weekNum}}</td>
            </tr>
            <tr scope="row">
                <td align="right">Match Date:</td>
                <td>
                    @isset ($result->MatchDate)
                        {{$result->MatchDate}}
                    @else
                        Empty
                    @endisset
                </td>
            </tr>


        </tbody>
    </table>
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$fixture->homeClub}} {{$fixture->homeChar}} vs. {{$fixture->awayClub}} {{$fixture->awayChar}}</h1>
</div>
<div class="container">
    <table class="table">
        <tbody>
            <tr scope="row">
                <td><div class="right">Home Team:</div></td>
                <td><div>{{$fixture->homeClub}} {{$fixture->homeChar}}</div></td>
            </tr>
            <tr scope="row">
                <td><div class="right">Away Team:</div></td>
                <td><div>{{$fixture->awayClub}} {{$fixture->awayChar}}</div></td>
            </tr>
            <tr scope="row">
                <td><div class="right">Venue:</div></td>
                <td><div>{{$fixture->venue}}</div></td>
            </tr>
            <tr scope="row">
                <td><div class="right">Division:</div></td>
                <td><div>{{$fixture->division}}</div></td>
            </tr>
            <tr scope="row">
                <td><div class="right">Week Number:</div></td>
                <td><div>{{$fixture->weekNum}}</div></td>
            </tr>
            <tr scope="row">
                <td><div class="right">Match Date:</div></td>
                <td><div>{{$fixture->MatchDate}}</div></td>
            </tr>
        </tbody>
    </table>
    @isset($players)
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Home</th>
                    <th>Away</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div>Player 1</td>
                    <td><div>{{$players->homeA1->playerName}}</div></td>
                    <td><div>{{$players->awayA1->playerName}}</div></td>
                </tr>
                <tr>
                    <td><div>Player 2</td>
                    <td><div>{{$players->homeA2->playerName}}</div></td>
                    <td><div>{{$players->awayA2->playerName}}</div></td>
                </tr>
                <tr>
                    <td><div>Player 3</td>
                    <td><div>{{$players->homeB1->playerName}}</div></td>
                    <td><div>{{$players->awayB1->playerName}}</div></td>
                </tr>
                <tr>
                    <td><div>Player 4</td>
                    <td><div>{{$players->homeB2->playerName}}</div></td>
                    <td><div>{{$players->awayB2->playerName}}</div></td>
                </tr>
            </tbody>
        </table>
        @foreach ($matches as $key => $match)
            <div class="container">
                <table class="table table-bordered">
                    <tr>
                    <th colspan="9">{{$key}}</th>
                    </tr>
                    <tr>
                        <td>Home</td>
                        @foreach ($match as $set)
                            <td>{{$set->homeScore}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Away</td>
                        @foreach ($match as $set)
                            <td>{{$set->awayScore}}</td>
                        @endforeach
                    </tr>
                </table>
            </div>
        @endforeach
    @else
        @if (!Auth::guest())
            logged
        @endif
    @endisset

</div>
@endsection
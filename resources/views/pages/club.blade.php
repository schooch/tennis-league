@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{$club}}</h1>
</div>
<div class="container">
    <table class="table table-cell-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col" colspan="2">team</th>
            </tr>
            <tr>
                    @foreach ($teams[0] as $team)
                        <th scope="col">{{$team->leagueType}}</th>
                    @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $char)
                <tr scope="row" class="table-row">
                    @foreach ($char as $team)
                        @isset ($team)
                            <td scope="col" class="table-link">
                                <a href="/{{$club}}/{{$team->leagueType}}{{$team->teamChar}}">
                                    {{$team->teamChar}}
                                </a>
                            </td>
                        @endisset
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
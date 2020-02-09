@extends('layouts.app')
@section('content')
<div class="container">
    <h1>
        {{$club}}
        @if (!Auth::guest() && Auth::user()->userType > 1)
            <a href="/{{$club}}/edit" class="btn btn-primary">
                Edit name
            </a>
            {!!Form::open(['action' => ['ClubController@destroy', $club], 'method' => 'DELETE', 'class' => 'pull-right'])!!}
            {{Form::submit('Delete Club', ['class' => 'btn btn-danger'])}}
            {!!Form::close()!!}
    </h1>
</div>
<div class="container">

    @endif
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
                    <td scope="col" class="table-link">
                        @isset ($team)
                                <a href="/{{$club}}/{{$team->leagueType}}{{$team->teamChar}}">
                                    {{$team->teamChar}}
                                </a>
                                @if (!Auth::guest() && Auth::user()->userType > 1)
                                <a href="/{{$club}}/{{$team->leagueType}}{{$team->teamChar}}/edit">
                                    Edit
                                </a>
                                @endif
                            @endisset
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (!Auth::guest() && Auth::user()->userType > 1)
    <a href="/{{$club}}/create" class="btn btn-primary">
        Add Team
    </a>
    @endif
</div>
@endsection
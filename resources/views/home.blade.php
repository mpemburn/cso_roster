@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (Auth::check())
                        Welcome to the <strong>Chesapeake Spokes Roster</strong> application.
                    @else
                        To use the <strong>Chesapeake Spokes Roster</strong>, please <a href="{{ url('/login') }}">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

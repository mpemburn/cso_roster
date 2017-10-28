@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (Auth::check())
                        @include('partials.welcome_message')
                    @else
                        To use the <strong>@lang('app.fullname')</strong>, please <a href="{{ url('/login') }}">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset Password</div>

                    <div class="panel-body text-center">
                        <h3>Sorry this password link has expired.</h3>
                        <h4>
                            <a href="{{ route('password.request') }}">
                                Click here to get a new link
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

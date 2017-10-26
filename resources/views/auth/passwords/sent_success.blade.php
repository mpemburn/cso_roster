@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body text-center">
                    <h4>@lang('passwords.sent') <strong>{{ $email }}</strong></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

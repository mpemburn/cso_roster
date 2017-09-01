@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    @if ($can_edit)
                        <?php $action = (!empty($member_id)) ? 'update' : 'store'; ?>
                        {{ Form::model($member, array('route' => array('member.' . $action, $member_id), 'id' => 'member_' . $action)) }}
                        {{ Form::hidden('user_id', $user_id)}}
                        {{ Form::hidden('MemberID', $member_id)}}
                    @endif
                    <div class="panel-heading">
                        <h4>{{ $member->first_name }} {{ $member->last_name }}</h4>
                        <div>Member ID: {{ $member_id }}</div>
                        <div>Member Since: {{ $member->since }}</div>
                    </div>
                    <div class="panel-body">
                        <main class="main-column col-md-12">
                            @if ($can_edit)
                                <div class="form-group">
                                    <label for="active"
                                           class="control-label">{{ Form::checkbox('Active', $member->is_active, $is_active) }}
                                        Active</label>
                                    <span class="saved hidden">SAVED</span>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-1 control-label">Name</label>
                                    <div class="col-md-11">
                                        {{ Form::select('prefix', $prefix_list, null, ['class' => 'col-md-1'])}}
                                        {{ Form::text('first_name', $member->first_name, ['class' => 'col-md-3 required', 'placeholder' => 'First Name *']) }}
                                        {{ Form::text('middle_name', $member->middle_name, ['class' => 'col-md-2', 'placeholder' => 'Middle Name']) }}
                                        {{ Form::text('last_name', $member->last_name, ['class' => 'col-md-3 required', 'placeholder' => 'Last Name *']) }}
                                        {{ Form::select('suffix', $suffix_list, null, ['class' => 'col-md-2']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-md-1 control-label">Address</label>
                                    <div class="col-md-11">
                                        {{ Form::text('address_1', $member->address_1, ['class' => 'col-md-10 required', 'placeholder' => 'Address 1 *']) }}
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        {{ Form::text('address_2', $member->address_2, ['class' => 'col-md-10', 'placeholder' => 'Address 2']) }}
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        {{ Form::text('city', $member->city, ['class' => 'col-md-4 required', 'placeholder' => 'City *']) }}
                                        {{ Form::select('state', $state_list, $member->state, ['class' => 'col-md-3 required']) }}
                                        {{ Form::text('zip', $member->zip, ['class' => 'col-md-2 required', 'placeholder' => 'Zip *']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-1 control-label">Email</label>
                                    <div class="col-md-11">
                                        {{ Form::text('email', $member->email, ['class' => 'col-md-10', 'placeholder' => 'Email']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phones" class="col-md-1 control-label">Phones</label>
                                    <div class="col-md-3">
                                        {{ Form::text('cell_phone', $member->cell_phone, ['class' => 'col-md-12', 'placeholder' => 'Cell Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        cell
                                    </div>
                                    <div class="col-md-3">
                                        {{ Form::text('home_phone', $member->home_phone, ['class' => 'col-md-12', 'placeholder' => 'Home Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        home
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comments" class="control-label col-md-1">Comments</label>
                                    <div class="col-md-7">
                                        {{ Form::textarea('comments', $member->comments, ['class' => 'col-md-12 member-comments']) }}
                                    </div>
                                    <div class="col-md-4 member-save-button">
                                        {{ Form::submit(($member_id == 0) ? 'Submit' : 'Update', ['id' => 'submit_update', 'class' => 'btn btn-primary', 'disabled' => 'disabled']) }}
                                        <i id="member_saving" class="member-saving fa fa-spinner fa-spin hidden"></i>
                                        <span class="saved hidden">SAVED</span>
                                    </div>
                                </div>
                                <div id="contacts" class="form-group boxed">
                                    @include('partials.member_contacts')
                                </div>
                                <div id="dues" class="form-group boxed">
                                    @include('partials.member_dues')
                                </div>
                            @else
                                @include('partials.member_static_main')
                            @endif
                        </main>
                        {{ Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error_dialog" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Validation Error</h4>
                </div>
                <div class="modal-body">
                    <p>One or more fields report errors:</p>
                    <ul id="error_messages"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modals">
        @include('partials.contact_modal')
        @include('partials.dues_modal')
    </div>
    @endsection
            <!-- Push any scripts needed for this page onto the stack -->
    @push('scripts')
    <script src="{{ URL::to('/js/lib') }}/jquery.dirtyforms.js"></script>
    <script>appSpace.authTimeout = '{!! trans('auth.timeout') !!}';</script>
    @endpush
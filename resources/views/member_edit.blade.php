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
                        {{ Form::hidden('member_id', $member_id)}}
                    @endif
                    <div class="panel-heading">
                        <h4>{{ $member->first_name }} {{ $member->last_name }}</h4>
                        <div>Member ID: {{ $member_id }}</div>
                    </div>
                    <div class="panel-body">
                        <main class="main-column col-md-12">
                            @if ($can_edit)
                                <div class="form-group">
                                    <label for="is_active"
                                           class="control-label">{{ Form::checkbox('is_active', $member->is_active, $is_active) }}
                                        Active</label>
                                    <span class="saved hidden">SAVED</span>
                                </div>
                                <div class="form-group">
                                    <label for="member_since_date" class="col-md-1 control-label">Since </label>
                                    <div class="col-md-11">
                                        <div class="col-md-2 field-wrapper">
                                            <div>{{ Form::text('member_since_date', $member->since, ['id' => 'member_since_date', 'class' => 'col-md-12 date-pick', 'placeholder' => 'Member Since']) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-1 control-label">Name</label>
                                    <div class="col-md-11">
                                        <div class="col-md-1 field-wrapper">
                                            {{ Form::select('prefix', $prefix_list, null, ['class' => 'col-md-12'])}}
                                        </div>
                                        <div class="col-md-3 field-wrapper">
                                            {{ Form::text('first_name', $member->first_name, ['class' => 'col-md-12 required', 'placeholder' => 'First Name *']) }}
                                        </div>
                                        <div class="col-md-2 field-wrapper">
                                            {{ Form::text('middle_name', $member->middle_name, ['class' => 'col-md-12', 'placeholder' => 'Middle Name']) }}
                                        </div>
                                        <div class="col-md-3 field-wrapper">
                                            {{ Form::text('last_name', $member->last_name, ['class' => 'col-md-12 required', 'placeholder' => 'Last Name *']) }}
                                        </div>
                                        <div class="col-md-1 field-wrapper">
                                            {{ Form::select('suffix', $suffix_list, null, ['class' => 'col-md-12 field-wrapper']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-md-1 control-label">Address</label>
                                    <div class="col-md-11">
                                        <div class="col-md-10 field-wrapper">
                                            {{ Form::text('address_1', $member->address_1, ['class' => 'col-md-12 required', 'placeholder' => 'Address 1 *']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        <div class="col-md-10 field-wrapper">
                                            {{ Form::text('address_2', $member->address_2, ['class' => 'col-md-12', 'placeholder' => 'Address 2']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        <div class="col-md-4 field-wrapper">
                                            {{ Form::text('city', $member->city, ['class' => 'col-md-12 required', 'placeholder' => 'City *']) }}
                                        </div>
                                        <div class="col-md-3 field-wrapper">
                                            {{ Form::select('state', $state_list, $member->state, ['class' => 'col-md-12 required']) }}
                                        </div>
                                        <div class="col-md-2 field-wrapper">
                                            {{ Form::text('zip', $member->zip, ['class' => 'col-md-12 required', 'placeholder' => 'Zip *']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-1 control-label">Email</label>
                                    <div class="col-md-11">
                                        <div class="col-md-10 field-wrapper">
                                            {{ Form::email('email', $member->email, ['class' => 'col-md-12 required', 'placeholder' => 'Email *']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phones" class="col-md-1 control-label">Phones</label>
                                    <div class="col-md-2">
                                        {{ Form::text('cell_phone', $member->cell_phone, ['class' => 'col-md-12', 'placeholder' => 'Cell Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        (cell)
                                    </div>
                                    <div class="col-md-2">
                                        {{ Form::text('home_phone', $member->home_phone, ['class' => 'col-md-12', 'placeholder' => 'Home Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        (home)
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-11">
                                        <div>{{ Form::text('google_group_date', $member->since, ['id' => 'google_group_date', 'class' => 'col-md-2 date-pick', 'placeholder' => 'Added to group']) }}</div>
                                        <label for="google_group_date" class="col-md-3 control-label left">&nbsp;Added
                                            to Google group</label>
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
                                @if($member_id != 0)
                                    <div id="contacts" class="form-group boxed">
                                        @include('partials.member_contacts')
                                    </div>
                                    <div id="dues" class="form-group boxed">
                                        @include('partials.member_dues')
                                    </div>
                                    <div id="board_roles" class="form-group boxed">
                                        @include('partials.board_roles')
                                    </div>
                                @endif
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
        @include('partials.board_role_modal')
    </div>
    @endsection
            <!-- Push any scripts needed for this page onto the stack -->
    @push('scripts')
    <script src="{{ URL::to('/js/lib') }}/jquery.dirtyforms.js"></script>
    <script>appSpace.authTimeout = '{!! trans('auth.timeout') !!}';</script>
    @endpush
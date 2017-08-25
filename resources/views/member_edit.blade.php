@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    @if ($can_edit)
                        {{ Form::model($member, array('route' => array('member.update', $member->id), 'id' => 'member_update')) }}
                        {{ Form::hidden('user_id', $user_id)}}
                        {{ Form::hidden('MemberID', $member->id)}}
                    @endif
                    <div class="panel-heading">
                        <h4>{{ $member->first_name }} {{ $member->last_name }}</h4>
                        Member ID: {{ $member->id }}
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
                                        {{ Form::select('Title', $prefix_list, null, ['class' => 'col-md-1'])}}
                                        {{ Form::text('First_Name', $member->first_name, ['class' => 'col-md-3 required', 'placeholder' => 'First Name *']) }}
                                        {{ Form::text('Middle_Name', $member->middle_name, ['class' => 'col-md-2', 'placeholder' => 'Middle Name']) }}
                                        {{ Form::text('Last_Name', $member->last_name, ['class' => 'col-md-3 required', 'placeholder' => 'Last Name *']) }}
                                        {{ Form::select('Suffix', $suffix_list, null, ['class' => 'col-md-2']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-md-1 control-label">Address</label>
                                    <div class="col-md-11">
                                        {{ Form::text('Address1', $member->address_1, ['class' => 'col-md-10 required', 'placeholder' => 'Address 1 *']) }}
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        {{ Form::text('Address2', $member->address_2, ['class' => 'col-md-10', 'placeholder' => 'Address 2']) }}
                                    </div>
                                    <div class="col-md-11 col-md-offset-1">
                                        {{ Form::text('City', $member->city, ['class' => 'col-md-4 required', 'placeholder' => 'City *']) }}
                                        {{ Form::select('State', $state_list, $member->state, ['class' => 'col-md-3 required']) }}
                                        {{ Form::text('Zip', $member->zip, ['class' => 'col-md-2 required', 'placeholder' => 'Zip *']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-1 control-label">Email</label>
                                    <div class="col-md-11">
                                        {{ Form::text('Email_Address', $member->email, ['class' => 'col-md-10', 'placeholder' => 'Email']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phones" class="col-md-1 control-label">Phones</label>
                                    <div class="col-md-3">
                                        {{ Form::text('Cell Phone', $member->cell_phone, ['class' => 'col-md-12', 'placeholder' => 'Cell Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        cell
                                    </div>
                                    <div class="col-md-3">
                                        {{ Form::text('Home Phone', $member->home_phone, ['class' => 'col-md-12', 'placeholder' => 'Home Phone']) }}
                                    </div>
                                    <div class="col-md-1 nopadding">
                                        home
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $count = 0; ?>
                                    @foreach($contacts as $contact)
                                        <label for="contacts" class="col-md-1 control-label"><?php echo ($count == 0 ) ? 'Contacts' : ''; ?></label>
                                        <div class="col-md-11">
                                            <div class="col-md-3">
                                                <strong>{{ $contact->name }}</strong>
                                                @if (!empty($contact->relationship))
                                                <strong> ({{ $contact->relationship }})</strong>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <strong>{{ $contact->phone_1 }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                @if (!empty($contact->phone_2))
                                                <strong>{{ $contact->phone_2 }}</strong>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                @if (!empty($contact->work_phone))
                                                Work <strong>{{ $contact->work_phone }}</strong>
                                                @endif
                                                @if (!empty($contact->phone_ext))
                                                Ext <strong>{{ $contact->phone_ext }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <?php $count++; ?>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label for="comments" class="control-label col-md-1">Comments</label>
                                    <div class="col-md-11">
                                        {{ Form::textarea('Comments', $member->comments, ['class' => 'col-md-8']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-11 col-md-offset-1">
                                        {{ Form::submit(($member->id == 0) ? 'Submit' : 'Update', ['id' => 'submit_update', 'class' => 'btn btn-primary', 'disabled' => 'disabled']) }}
                                        <i id="member_saving" class="member-saving fa fa-spinner fa-spin hidden"></i>
                                        <span class="saved hidden">SAVED</span>
                                    </div>
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
    @endsection
            <!-- Push any scripts needed for this page onto the stack -->
    @push('scripts')
    <script src="{{ URL::to('/js/lib') }}/jquery.dirtyforms.js"></script>
    <script>appSpace.authTimeout = '{!! trans('auth.timeout') !!}';</script>
    @endpush
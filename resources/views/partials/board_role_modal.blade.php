<div id="role_modal" class="role-modal modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><span class="modal_action"></span>Board Role</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body role-modal col-md-12">
                {{ Form::model($role, array('route' => array('role.update', null), 'name' => 'update_role', 'id' => 'update_role')) }}
                <header id="role_header">
                    {{ Form::hidden('member_id', $member_id, ['id' => 'role_member_id'])}}
                    {{ Form::hidden('id', 0, ['id' => 'role_id'])}}
                </header>
                <div class="form-group col-md-12">
                    <label for="title" class="col-md-2 control-label">Title</label>
                    <div class="col-md-5 field-wrapper">
                        {{ Form::select('board_role_id', $title_list, null, ['id' => 'board_role_id', 'class' => 'col-md-12']) }}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="start_date" class="col-md-2 control-label">Start Date</label>
                    <div class="col-md-3 field-wrapper">
                        {{ Form::text('start_date', '', ['id' => 'start_date', 'class' => 'col-md-12 date-pick-short required', 'placeholder' => 'mm/dd/yyyy *']) }}
                    </div>
                    <label for="end_date" class="col-md-3 control-label">End Date</label>
                    <div class="col-md-3 field-wrapper">
                        {{ Form::text('end_date', '', ['id' => 'end_date', 'class' => 'col-md-12 date-pick-short required', 'placeholder' => 'mm/dd/yyyy *']) }}
                    </div>
                </div>

                {{ Form::close()}}
            </div>
            <div class="modal-footer">
                <button id="role_delete" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <button id="role_save" type="button" class="btn btn-primary" disabled="disabled">Save</button>
                <button id="role_cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
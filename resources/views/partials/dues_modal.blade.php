<div id="dues_modal" class="dues-modal modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Membership Dues</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body dues-modal col-md-12">
                {{ Form::model($dues, array('route' => array('dues.update', null, 0), 'name' => 'update_dues', 'id' => 'update_dues')) }}
                <header id="dues_header">
                    {{ Form::hidden('member_id', 0, ['id' => 'member_id'])}}
                    {{ Form::hidden('id', 0, ['id' => 'id'])}}
                </header>
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">Year</label>
                    {{ Form::text('name', '', ['id' => 'name', 'class' => 'col-md-3 required', 'placeholder' => 'Name *']) }}
                    <label for="relationship" class="col-md-3 control-label">Relationship</label>
                    {{ Form::select('relationship', $relationship_list, null, ['id' => 'relationship', 'class' => 'col-md-2']) }}
                </div>
                <div class="form-group">
                    <label for="phone_one" class="col-md-2 control-label">Phone 1</label>
                    {{ Form::text('phone_one', '', ['id' => 'phone_one', 'class' => 'col-md-3 required', 'placeholder' => 'Phone 1 *']) }}
                    <label for="phone_two" class="col-md-3 control-label">Phone 2</label>
                    {{ Form::text('phone_two', '', ['id' => 'phone_two', 'class' => 'col-md-3', 'placeholder' => 'Phone 2']) }}
                </div>
                <div class="form-group">
                    <label for="work_phone" class="col-md-2 control-label">Work</label>
                    {{ Form::text('work_phone', '', ['id' => 'work_phone', 'class' => 'col-md-3', 'placeholder' => 'Work Phone']) }}
                    <label for="phone_ext" class="col-md-3 control-label">Extension</label>
                    {{ Form::text('phone_ext', '', ['id' => 'phone_ext', 'class' => 'col-md-3', 'placeholder' => 'Extension']) }}
                </div>

                {{ Form::close()}}
            </div>
            <div class="modal-footer">
                <button id="dues_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="contact_modal" class="contact-modal modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Contact</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body contact-modal col-md-12">
                {{ Form::model($contact, array('route' => array('contact.update', null), 'name' => 'update_contact', 'id' => 'update_contact')) }}
                <header id="contact_header">
                    {{ Form::hidden('member_id', $member_id, ['id' => 'contact_member_id'])}}
                    {{ Form::hidden('id', 0, ['id' => 'contact_id'])}}
                </header>
                <div class="form-group col-md-12">
                    <label for="name" class="col-md-2 control-label">Name</label>
                    {{ Form::text('name', '', ['id' => 'name', 'class' => 'col-md-4 required', 'placeholder' => 'Name *']) }}
                    <label for="relationship" class="col-md-3 control-label">Relationship</label>
                    {{ Form::select('relationship', $relationship_list, null, ['id' => 'relationship', 'class' => 'col-md-2']) }}
                </div>
                <div class="form-group col-md-12">
                    <label for="phone_one" class="col-md-2 control-label">Phone 1</label>
                    {{ Form::text('phone_one', '', ['id' => 'phone_one', 'class' => 'col-md-3 required', 'placeholder' => 'Phone 1 *']) }}
                    <label for="phone_two" class="col-md-3 control-label">Phone 2</label>
                    {{ Form::text('phone_two', '', ['id' => 'phone_two', 'class' => 'col-md-3', 'placeholder' => 'Phone 2']) }}
                </div>
                <div class="form-group col-md-12">
                    <label for="work_phone" class="col-md-2 control-label">Work</label>
                    {{ Form::text('work_phone', '', ['id' => 'work_phone', 'class' => 'col-md-3', 'placeholder' => 'Work Phone']) }}
                    <label for="phone_ext" class="col-md-3 control-label">Extension</label>
                    {{ Form::text('phone_ext', '', ['id' => 'phone_ext', 'class' => 'col-md-3', 'placeholder' => 'Extension']) }}
                </div>

                {{ Form::close()}}
            </div>
            <div class="modal-footer">
                <button id="contact_delete" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <button id="contact_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
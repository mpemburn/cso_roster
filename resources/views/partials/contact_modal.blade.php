<div id="contact_modal" class="contact-modal modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Line Item</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body contact-modal">
                {{ Form::model($contact, array('route' => array('contact.update', null, 0), 'name' => 'update_contact', 'id' => 'update_contact')) }}
                <header id="contact_header">
                    {{ Form::hidden('member_id', 0, ['id' => 'member_id'])}}
                    {{ Form::hidden('id', 0, ['id' => 'id'])}}
                </header>
                {{ Form::text('name', '', ['id' => 'name', 'class' => 'col-md-3 required', 'placeholder' => 'Name *']) }}
                {{ Form::select('relationship', $relationship_list, null, ['id' => 'relationship', 'class' => 'col-md-2']) }}

                {{ Form::close()}}
            </div>
            <div class="modal-footer">
                <button id="contact_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
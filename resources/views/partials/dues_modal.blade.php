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
                {{ Form::model($dues, array('route' => array('dues.update', null), 'name' => 'update_dues', 'id' => 'update_dues')) }}
                <header id="dues_header">
                    {{ Form::hidden('member_id', $member_id, ['id' => 'dues_member_id'])}}
                    {{ Form::hidden('id', 0, ['id' => 'dues_id'])}}
                </header>
                <div class="form-group col-md-12">
                    <label for="calendar_year" class="col-md-2 control-label">Year</label>
                    <div class="col-md-2 field-wrapper">
                        {{ Form::select('calendar_year', $calendar_year_list, null, ['id' => 'calendar_year', 'class' => 'col-md-12']) }}
                    </div>
                    <label for="phone_one" class="col-md-3 control-label">Paid Date</label>
                    <div class="col-md-4 field-wrapper">
                        {{ Form::text('paid_date', '', ['id' => 'paid_date', 'class' => 'col-md-12 required', 'placeholder' => 'mm/dd/yyyy *']) }}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="paid_amount" class="col-md-2 control-label">Amount $</label>
                    <div class="col-md-2 field-wrapper">
                        {{ Form::text('paid_amount', '', ['id' => 'paid_amount', 'class' => 'col-md-12', 'placeholder' => 'Amount *']) }}
                    </div>
                    <label for="helmet_fund" class="col-md-3 control-label">Helmet Fund</label>
                    <div class="col-md-2 field-wrapper">
                        {{ Form::select('helmet_fund', $helmet_fund_list, null, ['id' => 'helmet_fund', 'class' => 'col-md-12']) }}
                    </div>
                </div>

                {{ Form::close()}}
            </div>
            <div class="modal-footer">
                <button id="dues_delete" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                <button id="dues_save" type="button" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
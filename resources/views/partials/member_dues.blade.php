<?php $count = 0; ?>
<label for="dues" class="col-md-1 control-label bg-light-gray">&nbsp;Dues</label>
<div class="col-md-11 bg-light-gray">
    <div class="col-md-1">
        Year
    </div>
    <div class="col-md-2">
        Paid Date
    </div>
    <div class="col-md-2">
        Paid Amount
    </div>
</div>
@foreach($dues as $payment)
    <div class="col-md-1"></div>
    <div class="col-md-11">
        <div class="col-md-1">
            <strong>{{ $payment->calendar_year }}</strong>
        </div>
        <div class="col-md-2">
            <strong>{{ $payment->paid_date }}</strong>
        </div>
        <div class="col-md-2">
            <strong>{{ $payment->paid_amount }}</strong>
        </div>
    </div>
@endforeach

<?php $count = 0; ?>
<label for="dues" class="col-md-1 control-label bg-light-gray left"><i id="add_dues" class="fa fa-plus-circle add-circle"></i>Dues</label>
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
    <div class="col-md-2">
        Helmet Fund
    </div>
</div>
<?php $count = 0; ?>
@foreach($dues as $payment)
    <div data-id="{{ $payment->id }}" class="col-md-12 nopadding <?php echo ($count % 2 == 0) ? 'odd' : 'even' ?>">
        <div class="col-md-1"><i class="fa fa-pencil pull-right"></i></div>
        <div class="col-md-11">
            <div class="col-md-1">
                <strong>{{ $payment->calendar_year }}</strong>
            </div>
            <div class="col-md-2">
                <strong>{{ $payment->paid_date }}</strong>
            </div>
            <div class="col-md-2">
                <strong>${{ $payment->paid_amount }}</strong>
            </div>
            <div class="col-md-2">
                <strong>{{ $payment->helmet_fund_yn }}</strong>
            </div>
            <div class="col-md-5 text-right">
                <i class="fa fa-close text-danger" data-delete="{{ $payment->id }}"></i>
            </div>
        </div>
    </div>
    <?php $count++; ?>
@endforeach

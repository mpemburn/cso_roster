<?php $count = 0; ?>
@foreach($dues as $payment)
    <label for="dues" class="col-md-1 control-label"><?php echo ($count == 0 ) ? 'Dues' : ''; ?></label>
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
    <?php $count++; ?>
@endforeach

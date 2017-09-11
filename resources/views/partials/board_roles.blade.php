<label for="roles" class="col-md-1 control-label bg-light-gray left"><i id="add_role" class="fa fa-plus-circle add-circle"></i>Roles</label>
<div class="col-md-11 bg-light-gray">
    <div class="col-md-3">
        Title
    </div>
    <div class="col-md-2">
        Start Date
    </div>
    <div class="col-md-2">
        End Date
    </div>
</div>
<?php $count = 0; ?>
@foreach($roles as $role)
    <div data-id="{{ $role->id }}" class="col-md-12 nopadding <?php echo ($count % 2 == 0) ? 'odd' : 'even' ?>">
        <div class="col-md-1"><i class="fa fa-pencil pull-right"></i></div>
        <div class="col-md-11">
            <div class="col-md-3">
                <strong>{{ $role->title }}</strong>
            </div>
            <div class="col-md-2">
                <strong>{{ $role->start_date }}</strong>
            </div>
            <div class="col-md-2">
                <strong>{{ $role->end_date }}</strong>
            </div>
            <div class="col-md-5 text-right">
                <i class="fa fa-close text-danger" data-delete="{{ $role->id }}"></i>
            </div>
        </div>
    </div>
    <?php $count++; ?>
@endforeach

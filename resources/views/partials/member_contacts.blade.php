<label for="contacts" class="col-md-1 control-label bg-light-gray left"><i id="add_contact" class="fa fa-plus-circle add-circle"></i>Contacts</label>
<div class="col-md-11 bg-light-gray">
    <div class="col-md-3">
        Name (relationship)
    </div>
    <div class="col-md-2">
        Phone 1
    </div>
    <div class="col-md-2">
        Phone 2
    </div>
    <div class="col-md-4">
        Work Phone
    </div>
</div>
<?php $count = 0; ?>
@foreach($contacts as $contact)
    <div data-id="{{ $contact->id }}" class="col-md-12 nopadding <?php echo ($count % 2 == 0) ? 'odd' : 'even' ?>">
        <div class="col-md-1"><i class="fa fa-pencil pull-right"></i></div>
        <div class="col-md-11">
            <div class="col-md-3">
                <strong>{{ $contact->name }}</strong>
                @if (!empty($contact->relationship))
                    <strong> ({{ $contact->relationship }})</strong>
                @endif
            </div>
            <div class="col-md-2">
                <strong>{{ $contact->phone_one }}</strong>
            </div>
            <div class="col-md-2">
                @if (!empty($contact->phone_two))
                    <strong>{{ $contact->phone_two }}</strong>
                @endif
            </div>
            <div class="col-md-4">
                @if (!empty($contact->work_phone))
                    <strong>{{ $contact->work_phone }}</strong>
                @endif
                @if (!empty($contact->phone_ext))
                    Ext <strong>{{ $contact->phone_ext }}</strong>
                @endif
            </div>
        </div>
    </div>
    <?php $count++; ?>
@endforeach

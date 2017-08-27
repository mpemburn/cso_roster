<?php $count = 0; ?>
@foreach($contacts as $contact)
    <label for="contacts" class="col-md-1 control-label"><?php echo ($count == 0 ) ? 'Contacts' : ''; ?></label>
    <div class="col-md-11">
        <div class="col-md-3">
            <strong>{{ $contact->name }}</strong>
            @if (!empty($contact->relationship))
                <strong> ({{ $contact->relationship }})</strong>
            @endif
        </div>
        <div class="col-md-2">
            <strong>{{ $contact->phone_1 }}</strong>
        </div>
        <div class="col-md-2">
            @if (!empty($contact->phone_2))
                <strong>{{ $contact->phone_2 }}</strong>
            @endif
        </div>
        <div class="col-md-4">
            @if (!empty($contact->work_phone))
                Work <strong>{{ $contact->work_phone }}</strong>
            @endif
            @if (!empty($contact->phone_ext))
                Ext <strong>{{ $contact->phone_ext }}</strong>
            @endif
        </div>
    </div>
    <?php $count++; ?>
@endforeach

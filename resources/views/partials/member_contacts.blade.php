<label for="contacts" class="col-md-1 control-label bg-light-gray">&nbsp;Contacts <i id="add_contact" class="fa fa-plus-circle text-success"></i></label>
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
@foreach($contacts as $contact)
    <div class="col-md-1"></div>
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
@endforeach

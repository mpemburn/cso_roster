<div class="col-md-12">
    {{ $static->name }}
</div>
<div class="col-md-12">
    {{ $static->address1 }}
</div>
<div class="col-md-12">
    {{ $static->address2 }}
</div>
<div class="col-md-12">
    {{ $static->csz }}
</div>
<div class="col-md-12">
    <a href="mailto:{{ $static->email }}">{{ $static->email }}</a>
</div>
<div class="col-md-12 {{ (!empty($static->home_phone)) ? 'show' : 'hide' }}">
    <strong>Home:</strong> {{ $static->home_phone }}
</div>
<div class="col-md-12 {{ (!empty($static->cell_phone)) ? 'show' : 'hide' }}">
    <strong>Cell:</strong> {{ $static->cell_phone }}
</div>
<div class="col-md-12 {{ (!empty($static->work_phone)) ? 'show' : 'hide' }}">
    <strong>Work:</strong> {{ $static->work_phone }}
</div>
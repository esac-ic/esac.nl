@props(['name', 'id', 'label', 'value'])
<label for="{{ $id }}">{{ $label }}</label>
<div class="input-group date" id="{{ $id }}Box" data-target-input="nearest">
    <input type="text" class="form-control datetimepicker-input" id="{{ $id }}" name="{{ $name }}" data-target="#{{ $id }}Box" value="{{ $value }}" required>
    <div class="input-group-append" data-target="#{{ $id }}Box" data-toggle="datetimepicker">
        <div class="input-group-text"><i class="ion-calendar"></i></div>
    </div>
</div>

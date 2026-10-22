@props(['name', 'label', 'type' => 'text', 'value' => null, 'options' => null, 'col' => null])
@php($id = $attributes->get('id', $name))
@php($value = old(str_replace(['[', ']'], ['.', ''], $name), $value))
@php($value = $value instanceof BackedEnum ? $value->value : $value)
<div @class(['form-group', 'col-md-'.$col => $col])>
    <label for="{{ $id }}">{{ $label }}</label>
    @if ($options !== null)
        <select {{ $attributes->merge(['name' => $name, 'id' => $id])->class('form-control') }}>@foreach ($options as $key => $option)<option value="{{ $key }}" @selected((string) $key === (string) $value)>{{ $option }}</option>@endforeach</select>
    @elseif ($type === 'textarea')
        <textarea {{ $attributes->merge(['name' => $name, 'id' => $id, 'cols' => 50, 'rows' => 10])->class('form-control') }}>{{ $value }}</textarea>
    @elseif ($type === 'password')
        <input {{ $attributes->merge(['type' => $type, 'name' => $name, 'id' => $id])->class('form-control') }}>
    @else
        <input {{ $attributes->merge(['type' => $type, 'name' => $name, 'id' => $id, 'value' => $value])->class('form-control') }}>
    @endif
</div>

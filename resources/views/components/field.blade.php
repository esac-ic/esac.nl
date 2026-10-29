@props(['name', 'label', 'type' => 'text', 'value' => null, 'options' => null, 'col' => null, 'bag' => 'default'])
@php($id = $attributes->get('id', $name))
@php($key = str_replace(['[', ']'], ['.', ''], $name))
@php($value = old($key, $value))
@php($value = $value instanceof BackedEnum ? $value->value : $value)
@php($attributes = $attributes->class(['form-control', 'is-invalid' => $errors->getBag($bag)->has($key)]))
<div @class(['form-group', 'col-md-'.$col => $col])>
    <label for="{{ $id }}">{{ $label }}</label>
    @if ($options !== null)
        <select {{ $attributes->merge(['name' => $name, 'id' => $id]) }}>@foreach ($options as $optionValue => $option)<option value="{{ $optionValue }}" @selected((string) $optionValue === (string) $value)>{{ $option }}</option>@endforeach</select>
    @elseif ($type === 'textarea')
        <textarea {{ $attributes->merge(['name' => $name, 'id' => $id, 'cols' => 50, 'rows' => 10]) }}>{{ $value }}</textarea>
    @elseif ($type === 'password')
        <input {{ $attributes->merge(['type' => $type, 'name' => $name, 'id' => $id]) }}>
    @else
        <input {{ $attributes->merge(['type' => $type, 'name' => $name, 'id' => $id, 'value' => $value]) }}>
    @endif
    @error($key, $bag)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

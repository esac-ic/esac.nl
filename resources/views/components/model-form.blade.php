@props(['model' => null, 'resource' => null, 'action' => null, 'method' => null, 'cancel' => null, 'files' => false, 'submit' => 'Save'])
@php
    $action ??= $model->exists ? route("$resource.update", $model) : route("$resource.store");
    $method ??= $model->exists ? 'PATCH' : 'POST';
    $cancel ??= $model->exists ? route("$resource.show", $model) : route("$resource.index");
@endphp
<x-validation-errors />

{{ html()->form($method, $action)->acceptsFilesIf($files)->attribute('data-warn-unsaved')->open() }}
{{ $slot }}

<div class="my-4">
    {{ html()->submit($submit)->class('btn btn-primary') }}
    <a class="btn btn-outline-secondary" href="{{ $cancel }}">Cancel</a>
</div>
{{ html()->form()->close() }}

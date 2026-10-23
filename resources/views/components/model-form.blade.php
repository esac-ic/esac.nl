@props(['model' => null, 'resource' => null, 'action' => null, 'method' => null, 'cancel' => null, 'files' => false])
@php
    $action ??= $model->exists ? route("$resource.update", $model) : route("$resource.store");
    $method ??= $model->exists ? 'PATCH' : 'POST';
    $cancel ??= $model->exists ? route("$resource.show", $model) : route("$resource.index");
@endphp
<x-validation-errors />

{{ html()->form($method, $action)->acceptsFilesIf($files)->open() }}
{{ $slot }}

<div class="my-4">
    {{ html()->submit('Save')->class('btn btn-primary') }}
    <a class="btn btn-danger" href="{{ $cancel }}">Cancel</a>
</div>
{{ html()->form()->close() }}

@props(['title', 'model' => null, 'resource' => null, 'action' => null, 'method' => null, 'cancel' => null])
@php
    $action ??= $model->exists ? route("$resource.update", $model) : route("$resource.store");
    $method ??= $model->exists ? 'PATCH' : 'POST';
    $cancel ??= $model->exists ? route("$resource.show", $model) : route("$resource.index");
@endphp
<x-validation-errors />

<div class="card">
    <div class="card-header">
        <h3>{{ $title }}</h3>
    </div>
    <div class="card-body">
        {{ html()->form($method, $action)->open() }}
        {{ $slot }}
    </div>
</div>

<div class="my-4">
    {{ html()->submit('Save')->class('btn btn-primary') }}
    {{ html()->form()->close() }}
    <a class="btn btn-danger btn-close" href="{{ $cancel }}">Cancel</a>
</div>

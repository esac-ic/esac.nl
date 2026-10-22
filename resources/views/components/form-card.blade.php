@props(['fields', 'cancel', 'files' => false])
@include('includes.flash')
<x-validation-errors />

<div class="card">
    <div class="card-header">
        <h3>{{ $fields['title'] }}</h3>
    </div>
    <div class="card-body">
        {{ html()->form($fields['method'], $fields['url'])->if($files, fn ($form) => $form->acceptsFiles())->open() }}
        {{ $slot }}
    </div>
</div>

<div class="my-4">
    {{ html()->submit('Save')->class('btn btn-primary') }}
    {{ html()->form()->close() }}
    <a class="btn btn-danger btn-close" href="{{ $cancel }}">Cancel</a>
</div>

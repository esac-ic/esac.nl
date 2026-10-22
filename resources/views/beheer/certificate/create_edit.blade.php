@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @include('includes.flash')
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{$fields['title']}}</h3>
        </div>
        <div class="card-body">
            {{ html()->form($fields['method'], $fields['url'])->open() }}
            <div class="form-group">
                {{ html()->label('Name', 'name') }}
                {{ html()->text('name', ($certificate != null) ? $certificate->name : "")->class('form-control')->required() }}
            </div>
            <div class="form-group">
                {{ html()->label('Abbreviation', 'abbreviation') }}
                {{ html()->text('abbreviation', ($certificate != null) ? $certificate->abbreviation : "")->class('form-control')->required() }}
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ $certificate === null ? route('certificates.index') : route('certificates.show', $certificate) }}">{{'Cancel'}}</a>
    </div>
@endsection

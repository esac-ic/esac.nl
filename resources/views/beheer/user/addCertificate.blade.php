@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
<div class="container">
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}}</li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header"><h3>{{$fields['title']}}</h3></div>
        <div class="card-body">
            {{ html()->form($fields['method'], $fields['url'])->open() }}
            <div class="form-group">
                {{ html()->label('Certificate', 'certificate_id') }}
                {{ html()->select('certificate_id', $certificates)
                    ->value(($userCertificate != null) ? $userCertificate->certificate_id : "")
                    ->class('form-control')
                    ->required() }}
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ URL::previous() }}">{{'Cancel'}}</a>
    </div>
</div>
@endsection

@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
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
                {{ html()->text('name')
                    ->value(($rol != null) ? $rol->name : '')
                    ->class('form-control')
                    ->required() }}
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->button('Save', 'submit')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ ($rol == null) ? ('/rols') : ('/rols/' . $rol->id)}}">{{'Cancel'}}</a>
    </div>
@endsection

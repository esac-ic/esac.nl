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
                {{ html()->label('Title', 'title') }}
                {{ html()->text('title')
                    ->value(($book != null) ? $book->title : '')
                    ->class('form-control')
                    ->required() }}
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    {{ html()->label('Year', 'year') }}
                    {{ html()->number('year')
                        ->value(($book != null) ? $book->year : '')
                        ->class('form-control')
                        ->required() }}
                </div>
                <div class="form-group col-md-3">
                    {{ html()->label('Country', 'country') }}
                    {{ html()->text('country')
                        ->value(($book != null) ? $book->country : '')
                        ->class('form-control')
                        ->required() }}
                </div>
                <div class="form-group col-md-3">
                    {{ html()->label('Type', 'type') }}
                    {{ html()->text('type')
                        ->value(($book != null) ? $book->type : '')
                        ->class('form-control')
                        ->required() }}
                </div>
                <div class="form-group col-md-3">
                    {{ html()->label('Code', 'code') }}
                    {{ html()->text('code')
                        ->value(($book != null) ? $book->code : '')
                        ->class('form-control')
                        ->required() }}
                </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ ($book == null) ? ('/books') : ('/books/' . $book->id)}}">{{'Cancel'}}</a>
    </div>
@endsection

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

    <div id="app">
        {{ html()->form($fields['method'], $fields['url'])->open() }}
            <div class="card">
                <div class="card-header">
                    <h3>{{$fields['title']}}</h3>
                </div>
                <div class="card-body">
                    {{--hidden field with the amount of formrows--}}
                    {{ html()->input('hidden')->name('amount_of_formrows')->id('amount_of_formrows')->value('0') }}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            {{ html()->label('Name', 'name') }}
                            {{ html()->text('name')
                                ->value(($applicationForm != null) ? $applicationForm->name : '')
                                ->class('form-control')
                                ->required() }}
                        </div>
                    </div>
                </div>
            </div>
            <application-form :rows="{{ json_encode($rows) }}"></application-form>

            <div class="my-4">
                {{ html()->submit('Save')->class('btn btn-primary') }}
                <a class="btn btn-danger btn-close" href="{{ ($applicationForm == null) ? route('beheer.applicationForms.index') : route('beheer.applicationForms.show', $applicationForm->id)}}">
                    {{'Cancel'}}
                </a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection

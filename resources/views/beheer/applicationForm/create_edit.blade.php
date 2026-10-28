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

    <div id="app">
        {{ html()->form($fields['method'], $fields['url'])->open() }}
            <div class="card">
                <div class="card-header">
                    <h3>{{$fields['title']}}</h3>
                </div>
                <div class="card-body">
                    {{--hidden field with the amount of formrows--}}
                    <input name="amount_of_formrows" type="hidden" id="amount_of_formrows" value="0">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            {{ html()->label('Name', 'name') }}
                            {{ html()->text('name', ($applicationForm != null) ? $applicationForm->name : "")->class('form-control')->required() }}
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

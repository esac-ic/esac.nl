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
        {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            <div class="card">
                <div class="card-header">
                    <h3>{{$fields['title']}}</h3>
                </div>
                <div class="card-body">
                    {{--hidden field with the amount of formrows--}}
                    <input name="amount_of_formrows" type="hidden" id="amount_of_formrows" value="0">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            {!! Form::label('name', trans('ApplicationForm.nameNl')) !!}
                            {!! Form::text('nl_name', ($applicationForm != null) ? $applicationForm->applicationFormName->NL_text : "", ['class' => 'form-control','required' => 'required']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('name', trans('ApplicationForm.nameEn')) !!}
                            {!! Form::text('en_name', ($applicationForm != null) ? $applicationForm->applicationFormName->EN_text : "", ['class' => 'form-control','required' => 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <application-form :rows="{{ json_encode($rows) }}"></application-form>

            <div class="my-4">
                {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
                <a class="btn btn-danger btn-close" href="{{ ($applicationForm == null) ? route('beheer.applicationForms.index') : route('beheer.applicationForms.show', $applicationForm->id)}}">
                    {{trans('menu.cancel')}}
                </a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

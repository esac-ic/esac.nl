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
{{--            {!! Form::model($committee, ['route' => $fields['route']]) !!}--}}
            {!! Form::model($committee, ['url' => $fields['route'], 'method' => $fields['method']]) !!}
            <div class="form-row w-100">
                <div class="form-group col-md-4">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', options: ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('abbreviation', 'Abbreviation') !!}
                    {!! Form::text('abbreviation', options: ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', options: ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', options: ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
            </div>
            
            
            
{{--            --}}
{{--            <div class="form-group">--}}
{{--                {!! Form::label('name', 'Name') !!}--}}
{{--                {!! Form::text('name', ($book != null) ? $book->title : "", ['class' => 'form-control','required' => 'required']) !!}--}}
{{--            </div>--}}
{{--            <div class="form-row">--}}
{{--                <div class="form-group col-md-3">--}}
{{--                    {!! Form::label('year', 'Year') !!}--}}
{{--                    {!! Form::number('year', ($book != null) ? $book->year : "", ['class' => 'form-control','required' => 'required']) !!}--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-3">--}}
{{--                    {!! Form::label('country', 'Country') !!}--}}
{{--                    {!! Form::text('country', ($book != null) ? $book->country : "", ['class' => 'form-control','required' => 'required']) !!}--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-3">--}}
{{--                    {!! Form::label('type', 'Type') !!}--}}
{{--                    {!! Form::text('type', ($book != null) ? $book->type : "", ['class' => 'form-control','required' => 'required']) !!}--}}
{{--                </div>--}}
{{--                <div class="form-group col-md-3">--}}
{{--                    {!! Form::label('code', 'Code') !!}--}}
{{--                    {!! Form::text('code', ($book != null) ? $book->code : "", ['class' => 'form-control','required' => 'required']) !!}--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
    
    <div class="my-4">
        {!! Form::submit('Save', ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($committee == null) ? route('beheer.committees.index') : route('beheer.committees.show', $committee->id)}}">{{'Cancel'}}</a>
    </div>
    
@endsection

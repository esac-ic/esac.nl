@extends('layouts.app')

@section('content')
<div class="container intro-container" id="app">
    {!! Form::open(['method' => 'POST', 'url' => $route]) !!}
    {{ csrf_field() }}
        <div class="card">
            <div class="card-header">
                <h3>{{ $applicationForm->applicationFormName->text() }}</h3>
            </div>
            <div class="card-body">
                <registration-form rows="{{ json_encode($rows) }}" lang="{{  app()->getLocale() }}"></registration-form>
            </div>
        </div>

        <div class="my-4">
            {!! Form::submit(trans('menu.submit'), ['class'=> 'btn btn-primary'] ) !!}
            <a class="btn btn-danger btn-close" href="{{url($cancleRoute)}}">{{trans('menu.cancel')}}</a>
        </div>
    {!! Form::close() !!}
</div>
@endsection

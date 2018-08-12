@extends('layouts.beheer')

@section('title')
{{$applicationForm->applicationFormName->text()}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{$applicationForm->applicationFormName->text()}}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => 'POST',$route]) !!}
            <div class="form-group">
                {!! Form::label('user', trans('inschrijven.applicationFormUser')) !!}
                {!! Form::select('user',$users,null, ['class' => 'form-control','required' => 'required']) !!}
            </div>
            @foreach ($rows as $row)
                <div class="form-group">
                    {!! $row->getInputBox() !!}
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.submit'), ['class'=> 'btn btn-primary'] ) !!}
        {{ csrf_field() }}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{url($cancleRoute)}}">{{trans('menu.cancel')}}</a>
    </div>
@endsection

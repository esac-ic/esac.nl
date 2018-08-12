@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <div class="card">
        <div class="card-header">
            <h3>{{ $application_form->applicationFormName->text() }}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => 'POST',$route]) !!}

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
</div>
@endsection

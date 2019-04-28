@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$curPageName}}</h2>
                {!! $content !!}
            </div>
        </div>
    </div>

<div class="container">
	{|| Form::open(['url' => 'materiaalaanvraag']) ||}
	<div class="card mt-4" id="material-type">
		<div class="card-header">
			<h3>{{trans('material.type')}}</h3>
		</div>
		<div class="card-body">
			<div class="form-row">
				<div class="form-group col-md-5">
					{|| Form::label('materialtype', trans('material.type')) ||}
					{|| Form::text('materialtype', '', ['class' => 'form-control', 'required' => 'required']) ||}
					{|| Form::submit(trans('front-end/material.submit'), ['class'=> 'btn btn-primary']) ||}
					{|| Form::close() ||}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

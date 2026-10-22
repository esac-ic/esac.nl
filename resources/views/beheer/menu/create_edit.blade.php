@extends('layouts.beheer')

@section('title')
{{$fields['title_page']}}
@endsection

@section('content')
	@if($errors->any())
		<div class="alert alert-danger alert-dismissable" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			@foreach ($errors->all() as $error)
				{{ $errors->first() }}
			@endforeach
		</div>
	@endif
	<form class="form-horizontal" action="{{$fields['url']}}" method="POST">
		@csrf
		@method($fields['method'])
		@include('beheer.menu.create_edit_menu')
		@if($page === null || $page->editable)
			@include('beheer.menu.create_edit_page')
		@endif

		<div class="my-4">
			<button type="submit" class="btn btn-primary">{{'Save'}}</button>
			<a class="btn btn-danger btn-close" href="{{ $page === null ? route('pages.index') : route('pages.show', $page) }}">{{'Cancel'}}</a>
		</div>
	</form>
@endsection

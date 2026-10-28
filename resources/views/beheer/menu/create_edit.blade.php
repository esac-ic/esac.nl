@extends('layouts.beheer')

@section('title')
{{$fields['title_page']}}
@endsection

@section('content')
	@if($errors->any())
		<ul class="alert alert-danger">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
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

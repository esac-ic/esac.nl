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
	<form class="form-horizontal" action="{{url($fields['url'])}}" method="POST">
		@csrf
		@method($fields['method'])
		@include('beheer.menu.create_edit_menu')
		@if($page === null || $page->editable)
			@include('beheer.menu.create_edit_page')
		@endif

		<div class="my-4">
			<button type="submit" class="btn btn-primary">{{'Save'}}</button>
			<a class="btn btn-danger btn-close" href="{{ ($page == null) ? ('/pages') : ('/pages/' . $page->id)}}">{{'Cancel'}}</a>
		</div>
	</form>
@endsection

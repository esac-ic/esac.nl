@extends('layouts.beheer')

@section('title')
{{$fields['title_page']}}
@endsection

@section('content')
    @if($errors->any())
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            @foreach ($errors->all() as $error)
                {!! $errors->first() !!}
            @endforeach
        </div>
    @endif
    
    {{ html()->form($fields['method'], url($fields['url']))->open() }}
        @include('beheer.menu.create_edit_menu')
        @if($page === null || $page->editable)
            @include('beheer.menu.create_edit_page')
        @endif

        <div class="my-4">
            {{ html()->button('Save')->type('submit')->class('btn btn-primary') }}
            {{ html()->a(($page == null) ? '/pages' : '/pages/' . $page->id, 'Cancel')->class('btn btn-danger btn-close') }}
        </div>
    {{ html()->form()->close() }}
@endsection

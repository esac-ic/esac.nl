@extends('layouts.admin')

@section('title', $page->exists ? 'Edit page' : 'Add page')

@section('content')
    <x-validation-errors />
    <form class="form-horizontal" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST">
        @csrf
        @method($page->exists ? 'PATCH' : 'POST')
        <x-card :title="$page->exists ? 'Edit menu page' : 'Add menu item'">
            <div class="form-row">
                <x-field name="name" label="Name" :value="$page->name" col="6" required />
                @if(! $page->exists || $page->editable)
                    <x-field name="slug" label="URL" :value="$page->slug" col="6" required />
                @endif
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group form-check">
                        {{ html()->checkbox('show_in_menu', ! session()->hasOldInput() && $page->show_in_menu)->class('form-check-input') }}
                        {{ html()->label('Show page in menu', 'show_in_menu')->class('form-check-label') }}
                    </div>
                    <div class="form-group form-check">
                        {{ html()->checkbox('requires_login', ! session()->hasOldInput() && $page->requires_login)->class('form-check-input')->id('requires_login') }}
                        {{ html()->label('Only members who are logged in can view the page', 'requires_login')->class('form-check-label') }}
                    </div>
                </div>
            </div>
            @isset($parents)
                <div class="form-row mt-2">
                    <x-field name="parent_id" label="Sub page of" :options="['' => 'None, a top-level page'] + $parents->pluck('name', 'id')->all()" :value="$page->parent_id" col="6" />
                </div>
            @endisset
        </x-card>
        @if(! $page->exists || $page->editable)
            <x-card :title="$page->exists ? 'Edit page' : 'Add page'" class="mt-4">
                <x-field name="content" label="Content" type="textarea" :value="$page->content" />
            </x-card>
        @endif

        <div class="my-4">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-danger" href="{{ $page->exists ? route('admin.pages.show', $page) : route('admin.pages.index') }}">Cancel</a>
        </div>
    </form>
@endsection


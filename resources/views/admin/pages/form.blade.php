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
                    <x-field name="slug" label="Slug" :value="$page->slug" col="6" required />
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
            <div class="form-row mt-2" id="menu-parent-settings">
                <div class="form-group col-md-6">
                    <label for="menu_level"> Item type:  </label>
                    <select class="form-control" name="menu_level" id="menu_level">
                        <option value="top" @selected($page->parent_id == null)>Standalone menu item</option>
                        <option value="child" @selected($page->parent_id != null)>Sub menu item</option>
                    </select>
                </div>
                <div id="parent-page-group" class="col-md-6">
                    <label for="parent_id"> If page is a sub page, who is the parent? </label>
                    <select class="form-control" name="parent_id" id="parent_id">
                        @foreach($pages as $item)
                            <option value="{{$item->id}}" @selected($item->id === $page->parent_id)>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row" id="menu-position-settings">
                <x-field name="position" label="Position in the menu (lower comes first)" type="number" :value="$page->position" col="6" required />
            </div>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#menu_level').change(function() {
                $('#parent-page-group').toggle($('#menu_level').val() === 'child');
            });

            $('#show_in_menu').change(function () {
                $('#menu-parent-settings, #menu-position-settings').toggle($('#show_in_menu').is(':checked'));
            });

            $('#menu_level').change();
            $('#show_in_menu').change();
        });
    </script>
@endpush

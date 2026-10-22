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
                    <x-field name="url_name" label="URL name" :value="$page->url_name" col="6" required />
                @endif
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group form-check">
                        {{ html()->checkbox('menu_item', ! session()->hasOldInput() && $page->menu_item)->class('form-check-input')->id('menuItem') }}
                        {{ html()->label('Show page in menu', 'menuItem')->class('form-check-label') }}
                    </div>
                    <div class="form-group form-check">
                        {{ html()->checkbox('login', ! session()->hasOldInput() && $page->login)->class('form-check-input')->id('login') }}
                        {{ html()->label('User needs to be logged in to view the page', 'login')->class('form-check-label') }}
                    </div>
                </div>
            </div>
            <div class="form-row mt-2" id="menuSettingRow1">
                <div class="form-group col-md-6">
                    <label for="itemType"> Item type:  </label>
                    <select class="form-control" name="itemType" id="menuType">
                        <option value="standAlone" @selected($page->parent_id == null)>Standalone menu item</option>
                        <option value="subItem" @selected($page->parent_id != null)>Sub menu item</option>
                    </select>
                </div>
                <div id="parentItemBox" class="col-md-6 hidden">
                    <label for="parentItem"> If page is a sub page, who is the parent? </label>
                    <select class="form-control" name="parentItem" id="parentItem">
                        @foreach($pages as $item)
                            <option value="{{$item->id}}" @selected($item->id === $page->parent_id)>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row" id="menuSettingRow2">
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
            <a class="btn btn-danger btn-close" href="{{ $page->exists ? route('admin.pages.show', $page) : route('admin.pages.index') }}">Cancel</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#menuType').change(function() {
                $('#parentItemBox').toggle($('#menuType').val() === 'subItem');
            });

            $('#menuItem').change(function () {
                $('#menuSettingRow1, #menuSettingRow2').toggle($('#menuItem').is(':checked'));
            });

            $('#menuType').change();
            $('#menuItem').change();
        });
    </script>
@endpush

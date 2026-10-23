@extends('layouts.admin')

@section('title', $page->exists ? 'Edit page' : 'New page')

@section('content')
    <x-model-form :model="$page" resource="admin.pages">
        <x-card :title="$page->exists ? 'Edit page' : 'New page'">
            <div class="form-row">
                <x-field name="name" label="Name" :value="$page->name" col="6" required />
                @unless($page->hasFixedSlug())
                    <x-field name="slug" label="URL" :value="$page->slug" col="6" required />
                @endunless
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group form-check">
                        {{ html()->checkbox('show_in_menu', ! session()->hasOldInput() && $page->show_in_menu)->class('form-check-input') }}
                        {{ html()->label('Show page in menu', 'show_in_menu')->class('form-check-label') }}
                    </div>
                    @unless($page->hasFixedSlug())
                        <div class="form-group form-check">
                            {{ html()->checkbox('requires_login', ! session()->hasOldInput() && $page->requires_login)->class('form-check-input')->id('requires_login') }}
                            {{ html()->label('Only members who are logged in can view the page', 'requires_login')->class('form-check-label') }}
                        </div>
                    @endunless
                </div>
            </div>
            @isset($parents)
                <div class="form-row mt-2">
                    <x-field name="parent_id" label="Sub page of" :options="['' => 'None, a top-level page'] + $parents->pluck('name', 'id')->all()" :value="$page->parent_id" col="6" />
                </div>
            @endisset
        </x-card>
        @if(! $page->exists || $page->editable)
            <x-card title="Content" class="mt-4">
                <x-field name="body" label="Content" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$page->body" />
            </x-card>
        @endif
    </x-model-form>
@endsection


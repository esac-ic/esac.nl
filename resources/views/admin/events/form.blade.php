@extends('layouts.admin')

@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
    <x-validation-errors />

    {{ html()->form($event->exists ? 'PATCH' : 'POST', $event->exists ? route('admin.events.update', $event) : route('admin.events.store'))->acceptsFiles()->open() }}
    <x-card title="General information">
        <div class="form-row">
            <x-field type="datetime-local" name="start_date" label="Start date" :value="($event->start_date ?? now(config('app.display_timezone')))->toDateTimeLocalString('minute')" col="6" required />
            <x-field type="datetime-local" name="end_date" label="End date" :value="($event->end_date ?? now(config('app.display_timezone')))->toDateTimeLocalString('minute')" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="registration_form_id" label="Registration form" :options="['' => 'No registration form'] + $registrationForms" :value="$event->registration_form_id" col="6" />
            <x-field type="datetime-local" name="registration_deadline" label="Registration deadline (with a registration form)" :value="($event->exists ? ($event->registration_deadline ?? now(config('app.display_timezone'))) : now(config('app.display_timezone'))->subDays(7))->toDateTimeLocalString('minute')" col="6" />
        </div>
        <div class="form-row">
            <x-field name="event_category_id" label="Category" :options="$eventCategories" :value="$event->event_category_id" col="6" required />
            <div class="form-group col-md-6">
                {{ html()->label('Show the members\' certificates', 'shows_certificates') }}
                {{ html()->checkbox('shows_certificates', ! session()->hasOldInput() && ($event->shows_certificates ?? true), 1)->class('form-control') }}
            </div>
        </div>
    </x-card>
    <x-card title="Image" class="mt-4">
        <span>Image</span>
        <div class="form-group mt-2">
            <input type="file" class="form-control-file" id="image" name="image" @required(! $event->exists)>
        </div>
    </x-card>
    <x-card title="Content" class="mt-4">
        <x-field name="title" label="Title" :value="$event->title" required />
        <x-field name="short_description" label="Short description (max. 100 characters)" :value="$event->short_description" required maxlength="100" />
        <x-field name="body" label="Content" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$event->body" />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger" href="{{ $event->exists ? route('admin.events.show', $event) : route('admin.events.index') }}">Cancel</a>
    </div>
@endsection


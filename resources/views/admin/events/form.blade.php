@extends('layouts.admin')

@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
    <x-page-header :title="$event->exists ? 'Edit event' : 'New event'" />
    <x-model-form :model="$event" resource="admin.events" files>
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
                <div class="form-group col-md-6 d-flex align-items-end">
                    <div class="form-check mb-2">
                        {{ html()->checkbox('shows_certificates', ! session()->hasOldInput() && ($event->shows_certificates ?? true), 1)->class('form-check-input') }}
                        {{ html()->label('Show the members\' certificates', 'shows_certificates')->class('form-check-label') }}
                    </div>
                </div>
            </div>
        </x-card>
        <x-card title="Image" class="mt-4">
            <div class="form-group mb-0">
                <label for="image" class="sr-only">Image</label>
                <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('image')]) id="image" name="image" accept="image/*" @required(! $event->exists)>
                @if ($event->exists)
                    <small class="form-text text-muted">Leave empty to keep the current image.</small>
                @endif
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </x-card>
        <x-card title="Content" class="mt-4">
            <x-field name="title" label="Title" :value="$event->title" required />
            <x-field name="short_description" label="Short description (max. 100 characters)" :value="$event->short_description" required maxlength="100" />
            <x-field name="body" label="Content" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$event->body" />
        </x-card>
    </x-model-form>
@endsection


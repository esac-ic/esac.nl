@extends('layouts.admin')

@section('title', 'Contact details')

@section('content')
    <x-page-header title="Contact details" />
    <x-validation-errors />
    {{ html()->form('PUT', route('admin.contact-details.update'))->attribute('data-warn-unsaved')->open() }}
        <x-card title="In the footer">
            <div class="form-row">
                <x-field name="phone_number" label="Phone number" :value="$contact->phone_number" col="6" />
                <x-field name="email" label="Email address" type="email" :value="$contact->email" col="6" required />
                <x-field name="iban" label="IBAN" :value="$contact->iban" col="6" required />
                <x-field name="kvk_number" label="KvK number" :value="$contact->kvk_number" col="6" required />
            </div>
            <x-field name="facebook_url" label="Facebook" type="url" :value="$contact->facebook_url" />
            <x-field name="instagram_url" label="Instagram" type="url" :value="$contact->instagram_url" />
            <x-field name="youtube_url" label="YouTube" type="url" :value="$contact->youtube_url" />
        </x-card>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
        </div>
    {{ html()->form()->close() }}
@endsection

@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <x-validation-errors />
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <x-card title="Website">
            <div class="form-group form-check">
                <input type="hidden" name="settings[KILLSWITCH]" value="0">
                <input type="checkbox" class="form-check-input" name="settings[KILLSWITCH]" id="killswitch" value="1" @checked(old('settings.KILLSWITCH', $settings['KILLSWITCH']))>
                <label class="form-check-label" for="killswitch">Close the public website</label>
            </div>
            <x-field name="settings[FOOTER_PHONE_NUMBER]" id="footer_phone_number" label="Phone number in the footer" :value="$settings['FOOTER_PHONE_NUMBER']" />
            <x-field name="settings[BLOCKED_EMAIL_DOMAINS]" id="blocked_email_domains" label="Email domains that can't sign up, separated by ;" :value="$settings['BLOCKED_EMAIL_DOMAINS']" />
        </x-card>
        <x-card title="Mail lists per member type, separated by ;" class="mt-4">
            @foreach ($memberTypes as $type)
                <x-field :name="'settings['.$type->mailListSetting()->value.']'" :id="$type->value.'_mail_lists'" :label="$type->label()" :value="$settings[$type->mailListSetting()->value]" />
            @endforeach
        </x-card>

        <div class="my-4">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection

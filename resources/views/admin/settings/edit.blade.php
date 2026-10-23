@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <x-validation-errors />
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <x-card title="Website">
            <div class="form-group form-check">
                <input type="hidden" name="settings[killswitch]" value="0">
                <input type="checkbox" class="form-check-input" name="settings[killswitch]" id="killswitch" value="1" @checked(old('settings.killswitch', $settings['killswitch']))>
                <label class="form-check-label" for="killswitch">Close the public website</label>
            </div>
            <x-field name="settings[footer_phone_number]" id="footer_phone_number" label="Phone number in the footer" :value="$settings['footer_phone_number']" />
            <x-field name="settings[blocked_email_domains]" id="blocked_email_domains" label="Email domains that can't sign up, separated by ;" :value="$settings['blocked_email_domains']" />
        </x-card>
        <x-card title="Mail lists per member type, separated by ;" class="mt-4">
            @foreach ($memberTypes as $type)
                <x-field :name="'settings['.$type->mailListSetting()->value.']'" :id="$type->mailListSetting()->value" :label="$type->label()" :value="$settings[$type->mailListSetting()->value]" />
            @endforeach
        </x-card>

        <div class="my-4">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection

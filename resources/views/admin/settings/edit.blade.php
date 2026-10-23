@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <x-page-header title="Settings" />
    <x-validation-errors />
    {{ html()->form('PUT', route('admin.settings.update'))->attribute('data-warn-unsaved')->open() }}
        <x-card title="Website">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="killswitch" id="killswitch" value="1" @checked(old('killswitch', $settings->killswitch))>
                <label class="form-check-label" for="killswitch">Close the public website</label>
            </div>
            <x-field name="footer_phone_number" label="Phone number in the footer" :value="$settings->footer_phone_number" />
            <div class="form-group">
                <label for="blocked_email_domains">Email domains that can't sign up, one per line</label>
                <textarea class="form-control" name="blocked_email_domains" id="blocked_email_domains" rows="3">{{ implode("\n", old('blocked_email_domains', $settings->blocked_email_domains)) }}</textarea>
            </div>
        </x-card>
        <x-card title="Mail lists per member type" class="mt-4">
            <p class="text-muted">The mail lists members of each type are added to, one per line.</p>
            @foreach ($memberTypes as $type)
                <div class="form-group">
                    <label for="mail_lists_{{ $type->value }}">{{ $type->label() }}</label>
                    <textarea class="form-control" name="mail_lists[{{ $type->value }}]" id="mail_lists_{{ $type->value }}" rows="3">{{ implode("\n", old("mail_lists.$type->value", $settings->mail_lists[$type->value])) }}</textarea>
                </div>
            @endforeach
        </x-card>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
        </div>
    {{ html()->form()->close() }}
@endsection

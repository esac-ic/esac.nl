@extends('layouts.admin')

@php($title = $user->exists ? 'Edit member' : 'New member')

@section('title', $title)

@section('content')
    <x-validation-errors />

    <h1>{{ $title }}</h1>

    {{ html()->form($user->exists ? 'PATCH' : 'POST', $user->exists ? route('admin.members.update', $user) : route('admin.members.store'))->open() }}
    <x-card title="Personal" class="mt-4">
        <div class="form-row">
            <x-field name="first_name" label="First name" :value="$user->first_name" col="5" required :disabled="auth()->user()->cannot('manage-members')" />
            <x-field name="preposition" label="Preposition" :value="$user->preposition" col="2" :disabled="auth()->user()->cannot('manage-members')" />
            <x-field name="last_name" label="Last name" :value="$user->last_name" col="5" required :disabled="auth()->user()->cannot('manage-members')" />
        </div>
        <div class="form-row">
            <x-field name="email" label="Email address" :value="$user->email" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="password" label="Password" type="password" col="6" :required="! $user->exists" :minlength="$user->exists ? null : 8" />
            <x-field name="password_confirmation" label="Confirm password" type="password" col="6" :required="! $user->exists" />
        </div>
        <div class="form-row">
            <x-field name="street" label="Street" :value="$user->street" col="6" required />
            <x-field name="house_number" label="House number" :value="$user->house_number" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="zipcode" label="Postal code" :value="$user->zipcode" col="4" required />
            <x-field name="city" label="City" :value="$user->city" col="4" required />
            <x-field name="country" label="Country" :options="config('countries')" :value="$user->country ?? 'NL'" col="4" required />
        </div>
        <div class="form-row">
            <x-field name="phone_number" label="Phone number" :value="$user->phone_number" col="6" required />
            <x-field name="phone_number_alt" label="Alternative phone number" :value="$user->phone_number_alt" col="6" />
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-date-picker name="birthday" id="birthDay" label="Birthdate" :value="($user->birthday ?? now())->format('d-m-Y')" />
            </div>
            <x-field name="member_type" label="Member type" :options="$memberTypes" :value="$user->member_type" col="6" required :disabled="auth()->user()->cannot('manage-members')" />
        </div>
        <x-field name="remark" label="Remarks" :value="$user->remark" :disabled="auth()->user()->cannot('manage-members')" />
    </x-card>
    <x-card title="Financial" class="mt-4">
        <div class="form-row">
            <x-field name="bic" label="BIC" :value="$user->bic" col="4" />
            <x-field name="iban" label="IBAN" :value="$user->iban" col="8" required />
        </div>
        <div class="form-check">
            {{ html()->checkbox('direct_debit', ! session()->hasOldInput() && $user->direct_debit)->class('form-check-input')->id('direct_debit') }}
            {{ html()->label('Accept direct debit', 'direct_debit')->class('form-check-label') }}
        </div>
    </x-card>
    <x-card title="Emergency info" class="mt-4">
        <div class="form-row">
            <x-field name="emergency_street" label="Emergency address street" :value="$user->emergency_street" col="6" required />
            <x-field name="emergency_house_number" label="Emergency address house number" :value="$user->emergency_house_number" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="emergency_zipcode" label="Emergency postal code" :value="$user->emergency_zipcode" col="4" required />
            <x-field name="emergency_city" label="Emergency city" :value="$user->emergency_city" col="4" required />
            <x-field name="emergency_country" label="Emergency country" :options="config('countries')" :value="$user->emergency_country ?? 'NL'" col="4" required />
        </div>
        <x-field name="emergency_number" label="Emergency phone number" :value="$user->emergency_number" required />
    </x-card>
    @can('manage-members')
        <x-card title="Roles" class="mt-4">
            <div class="form-group">
                    @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{$role->id}}" id="role-{{ $role->id }}" @checked($user->roles->contains($role->id))>
                            <label class="form-check-label" for="role-{{ $role->id }}">{{$role->name}}</label>
                        </div>
                    @endforeach
            </div>
        </x-card>
    @endcan
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger" href="{{ $user->exists ? route('admin.members.show', $user) : route('admin.members.index') }}">Cancel</a>
    </div>

    {{ html()->form()->close() }}
@endsection

@push('scripts')
    @vite(['resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])
    <script type="module">
        $('#birthDayBox').datetimepicker({
            format: 'L',
            icons: {
                time: 'ion-clock',
                date: 'ion-calendar',
                up: 'ion-android-arrow-up',
                down: 'ion-android-arrow-down',
                previous: 'ion-chevron-left',
                next: 'ion-chevron-right',
                today: 'ion-calendar',
                clear: 'ion-trash-a',
                close: 'ion-close'
            }
        });
        $(function(){
            $('#birthDayBox').datetimepicker();
        })
    </script>
@endpush

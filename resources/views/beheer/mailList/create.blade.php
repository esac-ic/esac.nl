@extends('layouts.beheer')

@section('content')
    <x-form-card title="Add mailing list" :action="route('beheer.mail-lists.store')" method="POST" :cancel="route('beheer.mail-lists.index')">
        <div class="form-group">
            <label for="address">Email</label>
            <div class="input-group">
                <input class="form-control" type="text" name="address" id="address" value="{{ old('address') }}" required aria-describedby="basic-addon3">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon3">{{ config('services.mailman.domain') }}</span>
                </div>
            </div>
        </div>
    </x-form-card>
@endsection

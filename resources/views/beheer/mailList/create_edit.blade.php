@extends('layouts.beheer')

@section('content')
    <x-form-card :$fields :cancel="$mailList === null ? route('mailList.index') : route('mailList.show', $mailList->address)">
        <input type="hidden" name="id" value="{{ $mailList?->address }}">
        <div class="form-group">
            <label for="address">Email</label>
            <div class="input-group">
                <input class="form-control" type="text" name="address" id="address" value="{{ old('address', $mailList ? explode('@', $mailList->address)[0] : '') }}" required aria-describedby="basic-addon3">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon3">{{ config('services.mailman.domain') }}</span>
                </div>
            </div>
        </div>
    </x-form-card>
@endsection

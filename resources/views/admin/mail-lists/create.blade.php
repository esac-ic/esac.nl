@extends('layouts.admin')

@section('title', 'New mail list')

@section('content')
    <x-form-card title="New mail list" :action="route('admin.mail-lists.store')" method="POST" :cancel="route('admin.mail-lists.index')">
        <div class="form-group">
            <label for="address">Address</label>
            <div class="input-group">
                <input class="form-control" type="text" name="address" id="address" value="{{ old('address') }}" required aria-describedby="mail-list-domain">
                <div class="input-group-append">
                    <span class="input-group-text" id="mail-list-domain">{{ config('services.mailman.list_domain') }}</span>
                </div>
            </div>
        </div>
    </x-form-card>
@endsection

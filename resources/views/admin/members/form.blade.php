@extends('layouts.admin')

@php($title = $user->exists ? 'Edit member' : 'New member')

@section('title', $title)

@section('content')
    <x-validation-errors />

    <h1>{{ $title }}</h1>

    {{ html()->form($user->exists ? 'PATCH' : 'POST', $user->exists ? route('admin.members.update', $user) : route('admin.members.store'))->open() }}
    <x-member-fields :$user :$memberTypes />
    @can('manage-roles')
        <x-card title="Roles" class="mt-4">
            <div class="form-group">
                @foreach($roles as $role)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->value }}" id="role-{{ $role->value }}" @checked(in_array($role->value, old('roles', $user->roles?->map->value->all() ?? [])))>
                        <label class="form-check-label" for="role-{{ $role->value }}">{{ $role->label() }}</label>
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

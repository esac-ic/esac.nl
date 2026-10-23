@extends('layouts.admin')

@php($title = $user->exists ? 'Edit member' : 'New member')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>

    <x-model-form :model="$user" resource="admin.members">
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
    </x-model-form>
@endsection

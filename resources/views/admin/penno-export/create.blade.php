@extends('layouts.admin')

@section('title', 'Penno export')

@section('content')
    <x-form-card title="Penno export" :action="route('admin.penno-export.store')" method="POST" :cancel="route('admin.members.index')" submit="Download">
        <x-field name="days_ago" label="Number of days back" type="number" value="7" min="1" required />
    </x-form-card>
@endsection

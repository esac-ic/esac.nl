@extends('layouts.admin')

@section('title')
Registrations: {{ $event->title }}
@endsection

@section('content')
    <x-page-header :title="'Registrations: '.$event->title">
        <a href="{{route('admin.events.registrations.create', $event)}}" class="btn btn-primary">
            <em class="ion-person-add"></em> New registration
        </a>
        <a href="{{route('admin.events.registrations.export', $event)}}" class="btn btn-primary">
            <em class="ion-android-download"></em> Export
        </a>
        <a href="{{route('admin.events.index')}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em> Back
        </a>
    </x-page-header>
    <table class="table table-striped dt-responsive nowrap js-datatable" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email address</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->user->name }}</td>
                <td>{{ $registration->user->email }}</td>
                <td>{{ $registration->user->address }}</td>

                <td>
                    <a href="{{ route('admin.events.registrations.show', [$event, $registration]) }}"><span title="Show entered information" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" class="js-delete-registration" data-destroy-url="{{ route('admin.events.registrations.destroy', [$event, $registration]) }}"><span  class="ion-trash-a"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script type="module">
        $(document).on('click', '.js-delete-registration', function(){
            var url = $(this).attr('data-destroy-url');
            $.ajax({
                url: url,
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                method: 'DELETE',
                contentType: 'application/json',
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

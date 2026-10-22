@extends('layouts.beheer')

@section('title')
{{ 'Subscriptions: '.$agendaItem->title }}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header :title="'Subscriptions: '.$agendaItem->title">
        <a href="{{route('beheer.agenda-items.registrations.create', $agendaItem)}}" class="btn btn-primary">
            <em class="ion-person-add"></em> {{'New subscription'}}
        </a>
        <a href="{{route('beheer.agenda-items.registrations.export', $agendaItem)}}" class="btn btn-primary">
            <em class="ion-android-download"></em> {{'Export'}}
        </a>
        <a href="{{route('beheer.agenda-items.index')}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em> {{'Back'}}
        </a>
    </x-page-header>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Email address'}}</th>
            <th>{{"Adres"}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->user->getName() }}</td>
                <td>{{ $registration->user->email }}</td>
                <td>{{ $registration->user->getAddress() }}</td>

                <td>
                    <a href="{{ route('beheer.agenda-items.registrations.show', [$agendaItem, $registration]) }}"><span title="{{'Show entered information'}}" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" id="delete_button" data-url="{{ route('beheer.agenda-items.registrations.destroy', [$agendaItem, $registration]) }}"><span  class="ion-trash-a"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        $(document).on('click','#delete_button',function(){
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                method: 'DELETE',
                contentType: 'application/json',
                success: function(result) {
                    window.location.reload();
                },
                error: function(request,msg,error) {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <x-page-header :title="$pageTitle">
        <x-button-link :href="route('account.edit')" icon="edit">Edit</x-button-link>
    </x-page-header>
    <x-card :title="$user->name">
        <x-member-tabs :$user>
            <x-slot:certificates>
                <x-data-table :columns="['Name', 'Abbreviation']">
                    @foreach($user->certificates as $certificate)
                        <tr>
                            <td>{{ $certificate->name }}</td>
                            <td>{{ $certificate->abbreviation }}</td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:certificates>
            <x-slot:registrations>
                <x-data-table :columns="['Name', 'Start date', 'Action']">
                    @foreach ($user->registrations->sortBy('event.start_date') as $registration)
                        <tr>
                            <td><a href="{{ route('events.show', $registration->event) }}">{{ $registration->event->title }}</a></td>
                            <td>{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                @if($registration->event->isRegistrationOpen())
                                <form class="d-inline" method="post" action="{{ route('events.registration.destroy', $registration->event) }}" onsubmit="return confirm('Are you sure you want to remove your registration for this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0"><span title="Remove registration" class="ion-trash-a" aria-hidden="true"></span></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:registrations>
        </x-member-tabs>
    </x-card>
</div>
@endsection

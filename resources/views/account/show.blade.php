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
                    @forelse($user->certificates as $certificate)
                        <tr>
                            <td>{{ $certificate->name }}</td>
                            <td>{{ $certificate->abbreviation }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">No certificates</td></tr>
                    @endforelse
                </x-data-table>
            </x-slot:certificates>
            <x-slot:registrations>
                <x-data-table :columns="['Name', 'Start date', 'Actions']">
                    @forelse ($user->registrations->sortBy('event.start_date') as $registration)
                        <tr>
                            <td><a href="{{ route('events.show', $registration->event) }}">{{ $registration->event->title }}</a></td>
                            <td>{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                @if($registration->event->isRegistrationOpen())
                                    <x-actions-menu :label="$registration->event->title">
                                        <x-actions-menu.delete :action="route('events.registration.destroy', $registration->event)" title="Unregister" :confirm="'Unregister from '.$registration->event->title.'?'" button="Unregister">Unregister</x-actions-menu.delete>
                                    </x-actions-menu>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">No registrations</td></tr>
                    @endforelse
                </x-data-table>
            </x-slot:registrations>
        </x-member-tabs>
    </x-card>
</div>
@endsection

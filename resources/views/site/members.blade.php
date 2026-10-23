@extends('layouts.app')

@section('content')
    <x-page-intro :title="$pageTitle" :$content />

    <section class="py-3">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <x-data-table datatable :columns="['Name', 'Email address', 'Phone number', 'Member type']">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone_number}}</td>
                                <td>{{ $user->member_type?->label() }}</td>
                            </tr>
                        @endforeach
                    </x-data-table>
                </div>
            </div>
        </div>
    </section>
@endsection

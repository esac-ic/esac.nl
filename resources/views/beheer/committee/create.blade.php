@extends('layouts.beheer')

@section('title')
    {{'Create new committee'}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ 'Create new committee' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('beheer.committees.store') }}" method="POST" novalidate>
                @csrf
                @include('beheer.committee._form', ['cancelUrl' => route("beheer.committees.index")])
            </form>
        </div>
    </div>
@endsection
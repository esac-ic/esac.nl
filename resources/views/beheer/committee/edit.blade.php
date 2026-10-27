@extends('layouts.beheer')

@section('title')
    {{'Edit ' . $committee->name}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{'Edit committee'}}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('beheer.committees.update', $committee) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('beheer.committee._form', ['cancelUrl' => route("beheer.committees.show", $committee)])
            </form>
        </div>
    </div>
@endsection
@extends('layouts.app',[
    'curPageName' => 'No access'
])

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$curPageName}}</h2>
                <p>{{'You do not have sufficient access to view this page'}}</p>
            </div>
        </div>
    </div>
@endsection


@extends('layouts.app',[
    'curPageName' => 'Page not found'
])

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                @if (isset($curPageName))
                    <h2 class="card-title">{{$curPageName}}</h2>
                @endif
                <p>{{'Page not found'}}</p>
            </div>
        </div>
    </div>
@endsection

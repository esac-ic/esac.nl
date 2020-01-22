@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$curPageName}}</h2>
                {!! clean($content) !!}
            </div>
        </div>
    </div>
@endsection

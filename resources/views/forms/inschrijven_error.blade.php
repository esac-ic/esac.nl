@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                {{--Start of custom text--}}
                <h2 class="card-title">Error</h3>
                <p class="card-text">{{$error}}</p>
                {{--End of custom text--}}
            </div>
        </div>
    </div>
@endsection

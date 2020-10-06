@extends('layouts.app',[
    'curPageName' => trans('auth.404')
])

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                @if (isset($curPageName))
                    <h2 class="card-title">{{$curPageName}}</h2>
                @endif
                <p>{{trans('validation.PageNotFound')}}</p>
            </div>
        </div>
    </div>
@endsection

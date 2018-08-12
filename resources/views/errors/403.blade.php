@extends('layouts.app',[
    'curPageName' => trans('auth.403')
])

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$curPageName}}</h2>
                <p>{{trans('validation.Unauthorized')}}</p>
            </div>
        </div>
    </div>
@endsection


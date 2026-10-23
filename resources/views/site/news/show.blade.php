@extends('layouts.app')
@section('content')
<div class="container intro-container pb-3">
    <x-news-card :$newsItem />
    <a class="btn btn-outline-primary mt-3" href="{{ route('news.index') }}"><span class="ion-android-arrow-back" aria-hidden="true"></span> Back to news</a>
</div>
@endsection

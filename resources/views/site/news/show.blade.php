@extends('layouts.app')
@section('content')
<div class="container intro-container pb-3">
    <x-news-card :$newsItem />
</div>
@endsection

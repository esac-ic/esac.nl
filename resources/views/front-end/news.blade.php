@extends('layouts.app')
@section('content')
<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $curPageName }}</h2>
            {!! $content !!}
        </div>
    </div>
</div>

<section class="py-3">
    <div class="container">
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($newsItems as $newsItem)
            <div class="col-lg-4 col-md-6 d-flex flex-wrap">
                <div class="card w-100 position-relative">
                    @if($newsItem->image_url != "")
                    <a href="/nieuws/{{ $newsItem->id }}">
                        <img class="card-img-top" src="{{$newsItem->getImageUrl()}}" alt="Card image cap">
                    </a>
                    @endif
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{\Carbon\Carbon::parse($newsItem->created_at)->format('d M')}}</span>
                    <div class="card-body">
                        <a href="/nieuws/{{ $newsItem->id }}">
                            <h4 class="card-title">{{$newsItem->newsItemTitle->text()}}</h4>
                            <p class="card-text text-body">{{ strip_tags(str_limit($newsItem->newsItemText->text(), $limit = 150, $end = '...')) }}</p>
                        </a>
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <span class="ion-person"></span> {{$newsItem->author}}
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-outline-primary" href="/nieuws/{{$newsItem->id}}">{{trans('front-end/news.read_more')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{ $newsItems->links('front-end.pagination') }}
</section>


@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
<script type="text/javascript">
    $('#dataTable').DataTable();
</script>
@endpush
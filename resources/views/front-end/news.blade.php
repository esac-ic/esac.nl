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

    <section class="pt-3">
        <div class="container">
            @foreach($newsItems as $newsItem)
                <div class="card">
                  <div class="position-relative">
                  @if($newsItem->image_url != "")
                    <img class="card-img-top" src="{{$newsItem->getImageUrl()}}" alt="Card image cap">
                  @endif
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{\Carbon\Carbon::parse($newsItem->created_at)->format('d M')}}</span>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">{{$newsItem->newsItemTitle->text()}}</h4>
                    <p class="card-text text-body">
                        {!! $newsItem->newsItemText->text() !!}
                    </p>
                  </div>
                  <div class="card-footer bg-white p-3">
                    <div class="row justify-content-between">
                      <div class="col-auto text-muted">
                        <span class="ion-person"></span> {{$newsItem->getCreatedBy->getName()}}</a>
                      </div>
                    </div>
                  </div>
                </div>
            @endforeach
            {{ $newsItems->links('front-end.pagination') }}
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
<script type="text/javascript">
    $('#dataTable').DataTable();
</script>
@endpush
@extends('layouts.beheer')

@section('title')
{{trans("NewsItem.newsItem")}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans("NewsItem.newsItem")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/newsItems/'.$newsItem->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/newsItems/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                    </a>
                @endif
                {{ Form::open(array('url' => 'newsItems/' . $newsItem->id, 'method' => 'delete')) }}
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('NewsItem.defaultInfo')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{trans('NewsItem.title')}}</td>
                    <td>{{$newsItem->newsItemTitle->text()}}</td>
                </tr>
                <tr>
                    <td>{{trans('NewsItem.placeDate')}}</td>
                    <td>{{\Carbon\Carbon::parse($newsItem->created_at)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{trans('NewsItem.createdBy')}}</td>
                    <td>{{$newsItem->getCreatedBy->getName()}}</td>
                </tr>
                @if($newsItem->image_url != "")
                    <tr>
                        <td>{{trans("NewsItem.newsImage")}}</td>
                        <td><img src="{{$newsItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('NewsItem.text') }}</h3>
        </div>
        <div class="card-body">
            {!! $newsItem->newsItemText->text() !!}
        </div>
    </div>
@endsection
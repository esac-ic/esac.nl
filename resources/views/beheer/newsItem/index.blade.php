@extends('layouts.beheer')

@section('title')
{{trans("NewsItem.newsItems")}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans("NewsItem.newsItems")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('newsItems/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("NewsItem.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('NewsItem.title')}}</th>
            <th>{{trans('NewsItem.createdBy')}}</th>
            <th>{{trans('NewsItem.placeDate')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($newsItems as $newsItem)
            <tr>
                <td>{{$newsItem->newsItemTitle->text()}}</td>
                <td>{{$newsItem->getCreatedBy->getName()}}</td>
                <td>{{\Carbon\Carbon::parse($newsItem->created_at)->format('d-m-Y h:i')}}</td>
                <td>
                    <a href="{{url('/newsItems/' . $newsItem->id . '/edit')}}"><span title="{{trans('NewsItem.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/newsItems/'. $newsItem->id)}}"><span title="{{trans("NewsItem.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
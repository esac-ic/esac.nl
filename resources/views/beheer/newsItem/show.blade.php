@extends('layouts.beheer')

@section('title')
{{'News item'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'News item'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/newsItems/'.$newsItem->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/newsItems/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{'Back'}}
                    </a>
                @endif
                {{ html()->form('DELETE', url('newsItems/' . $newsItem->id))->open() }}
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'General information'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{'Title'}}</td>
                    <td>{{$newsItem->title}}</td>
                </tr>
                <tr>
                    <td>{{'Created at'}}</td>
                    <td>{{\Carbon\Carbon::parse($newsItem->created_at)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{'Created by'}}</td>
                    <td>{{$newsItem->author}}</td>
                </tr>
                @if($newsItem->image_url != "")
                    <tr>
                        <td>{{'Image'}}</td>
                        <td><img src="{{$newsItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'News message' }}</h3>
        </div>
        <div class="card-body">
            {!! $newsItem->text !!}
        </div>
    </div>
@endsection

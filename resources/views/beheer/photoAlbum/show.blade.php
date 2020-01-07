@extends('layouts.beheer')

@section('title')
{{trans("photoAlbum.photoAlbums")}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans("photoAlbum.photoAlbums")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/photoAlbums/'.$photoAlbum->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{url('/photoAlbums/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'photoAlbums/' . $photoAlbum->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('photoAlbum.photoAlbum')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{trans('photoAlbum.title')}}</td>
                    <td>{{ $photoAlbum->title }}</td>
                </tr>
                <tr>
                    <td>{{trans('photoAlbum.description')}}</td>
                    <td>{{ $photoAlbum->description }}</td>
                </tr>
                <tr>
                    <td>{{trans('photoAlbum.date')}}</td>
                    <td>{{ $photoAlbum->date }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
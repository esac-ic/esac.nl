@extends('layouts.beheer')

@section('title')
{{trans("photoAlbum.photoAlbums")}}
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
            <h1>{{trans("photoAlbum.photoAlbums")}}</h1>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('photoAlbum.title')}}</th>
            <th>{{trans('photoAlbum.date')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($photoAlbums as $photoAlbum)
            <tr>
                <td>{{$photoAlbum->title}}</td>
                <td>{{\Carbon\Carbon::parse($photoAlbum->date)->format('d-m-Y')}}</td>
                <td>
                    <a href="{{url('/photoAlbums/' . $photoAlbum->id . '/edit')}}"><span title="{{trans('photoAlbum.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/photoAlbums/'. $photoAlbum->id)}}"><span title="{{trans("photoAlbum.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@extends('layouts.beheer')

@section('title')
{{trans("certificate.certificaat")}}
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
            <h1>{{trans("certificate.certificaat")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('certificates/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("certificate.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('certificate.name')}}</th>
            <th>{{trans('certificate.abbreviation')}}</th>
            <th>{{trans('certificate.duration')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($certificates as $certificate)
            <tr >
                <td>{{$certificate->certificateName->text()}}</td>
                <td>{{$certificate->abbreviation}}</td>
                <td>{{$certificate->duration === 0 ? "" : $certificate->duration }}</td>
                <td>
                    <a href="{{url('/certificates/' . $certificate->id . '/edit')}}"><span title="{{trans('certificate.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/certificates/'. $certificate->id)}}"><span title="{{trans("certificate.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
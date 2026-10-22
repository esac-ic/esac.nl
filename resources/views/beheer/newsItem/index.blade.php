@extends('layouts.beheer')

@section('title')
{{'News items'}}
@endsection

@section('content')
    @include('includes.flash')

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'News items'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{route('newsItems.create')}}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New news item '}}
                </a>
            </div>
        </div>
    </div>
    <table id="newsItems" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Title'}}</th>
            <th>{{'Created by'}}</th>
            <th>{{'Created at'}}</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($newsItems as $newsItem)
            <tr>
                <td>{{$newsItem->title}}</td>
                <td>{{$newsItem->author}}</td>
                <td>{{ $newsItem->created_at->tz(config('app.display_timezone'))->format('d-m-Y H:i') }}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{route('newsItems.edit', $newsItem)}}"><span title="Edit news item" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{route('newsItems.show', $newsItem)}}"><span title="Show news item" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js'])
    <script type="module">
        $('#newsItems').DataTable({
            columnDefs: [
                {type: 'de_datetime', targets: 1},
                {type: 'de_datetime', targets: 2},
            ]
        })
    </script>
@endpush

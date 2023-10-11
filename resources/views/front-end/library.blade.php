@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

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
            <div class="card">
                <div class="card-body">
                    <table id="dataTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{'Title'}}</th>
                                <th>{{'Year'}}</th>
                                <th>{{'Country'}}</th>
                                <th>{{'Type'}}</th>
                                <th>{{'Code'}}</th>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                        @foreach ($books as $book)
                            <tr>
                                <td>{{$book->title}}</td>
                                <td>{{$book->year}}</td>
                                <td>{{$book->country}}</td>
                                <td>{{$book->type}}</td>
                                <td>{{$book->code}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}"></script>
<script>
    $('#dataTable').DataTable();
</script>
@endpush
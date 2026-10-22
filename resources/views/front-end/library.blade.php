@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@section('content')
    <x-page-intro :title="$curPageName" :$content />

    <section class="py-3">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <x-data-table id="dataTable" :columns="['Title', 'Year', 'Country', 'Type', 'Code']">
                        @foreach ($books as $book)
                            <tr>
                                <td>{{$book->title}}</td>
                                <td>{{$book->year}}</td>
                                <td>{{$book->country}}</td>
                                <td>{{$book->type}}</td>
                                <td>{{$book->code}}</td>
                            </tr>
                        @endforeach
                    </x-data-table>
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
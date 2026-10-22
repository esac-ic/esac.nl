@extends('layouts.app')


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
@vite(['resources/assets/js/vendor/datatables.js'])
<script type="module">
    $('#dataTable').DataTable();
</script>
@endpush
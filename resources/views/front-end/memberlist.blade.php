@extends('layouts.app')


@section('content')
    <x-page-intro :title="$curPageName" :$content />

    <section class="py-3">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <x-data-table id="dataTable" :columns="['Name', 'Email address', 'Phone number', 'Type of member']">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->getName()}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone_number}}</td>
                                <td>{{ $user->kind_of_member?->label() }}</td>
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

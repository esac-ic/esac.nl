@props(['columns', 'datatable' => false])
{{-- With datatable, the table becomes a sortable, searchable DataTable. --}}
<table {{ $attributes->class(['table table-striped dt-responsive nowrap', 'js-datatable' => $datatable]) }} style="width:100%">
    <thead>
    <tr>
        @foreach ($columns as $column)
            <th>{{ $column }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    {{ $slot }}
    </tbody>
</table>
@if ($datatable)
    @once
        @push('scripts')
            @vite(['resources/assets/js/vendor/datatables.js'])
            <script type="module">
                $('.js-datatable').DataTable();
            </script>
        @endpush
    @endonce
@endif

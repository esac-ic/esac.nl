@props(['columns', 'datatable' => false])
{{-- With datatable, the table becomes a sortable, searchable DataTable. --}}
<table {{ $attributes->class(['table table-striped dt-responsive nowrap', 'js-datatable' => $datatable]) }} style="width:100%">
    <thead>
    <tr>
        @foreach ($columns as $column)
            {{-- DataTables keeps the "all" columns visible on narrow screens; the rest fold into the row details. --}}
            <th @class(['all' => $loop->first || $column === 'Actions']) @if ($column === 'Actions') data-orderable="false" @endif>{{ $column }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    {{ $slot }}
    </tbody>
</table>
@if ($datatable)
    @once
        @push('styles')
            <link rel="stylesheet" type="text/css" href="{{ mix('css/vendor/datatables.css') }}">
        @endpush
        @push('scripts')
            <script src="{{ mix('js/vendor/datatables.js') }}"></script>
            <script>
                $('.js-datatable').DataTable();
            </script>
        @endpush
    @endonce
@endif

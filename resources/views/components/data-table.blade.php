@props(['columns', 'datatable' => false])
{{-- With datatable, the admin layout makes the table a sortable, searchable DataTable. --}}
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

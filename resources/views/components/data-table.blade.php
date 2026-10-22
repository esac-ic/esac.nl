@props(['columns'])
<table {{ $attributes->merge(['id' => 'users'])->class('table table-striped dt-responsive nowrap') }} style="width:100%">
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

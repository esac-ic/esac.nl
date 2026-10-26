{{-- A sidebar link; it shows as current on every route that matches active, e.g. admin.events.* --}}
@props(['route', 'active' => null])
@php($current = request()->routeIs($active ?? $route))
<li>
    <a href="{{ route($route) }}" @class(['admin-nav-link', 'active' => $current]) @if ($current) aria-current="page" @endif>{{ $slot }}</a>
</li>

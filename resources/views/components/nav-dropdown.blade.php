@props(['label', 'active' => false])
@php($id = 'nav-dropdown-'.str($label)->slug())
<li class="nav-item dropdown">
    <a @class(['nav-link dropdown-toggle', 'active' => $active]) href="#" id="{{ $id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $label }}
    </a>
    <div class="dropdown-menu" aria-labelledby="{{ $id }}">
        {{ $slot }}
    </div>
</li>

@props(['label'])
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $label }}
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        {{ $slot }}
    </div>
</li>

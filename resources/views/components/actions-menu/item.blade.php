@props(['href', 'icon'])
<a class="dropdown-item" href="{{ $href }}"><span class="ion-{{ $icon }} text-muted mr-2" aria-hidden="true"></span>{{ $slot }}</a>

@props(['href', 'icon'])
<a class="dropdown-item" href="{{ $href }}"><x-dynamic-component :component="'heroicon-o-'.$icon" class="mr-2" />{{ $slot }}</a>

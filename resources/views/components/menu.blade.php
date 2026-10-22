@props(['navClass', 'navId' => ''])
<div class="navbar-container">
    <div id="{{ $navId }}" class="{{ $navClass }} navbar-dark">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <x-logo />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="ion-navicon h3 text-white"></span>
                </button>
                
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        @foreach($menuItems as $menuItem)
                            @if(count($subMenuItems[$menuItem->id]) > 0)
                                {{--has submenu items so it wil be a dropdown menu--}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{$menuItem->name}}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        @foreach($subMenuItems[$menuItem->id] as $subItem)
                                            <a class="dropdown-item"
                                               href="{{ route('front-end.page', $subItem->url_name) }}">{{$subItem->name}}</a>
                                        @endforeach
                                    </div>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('front-end.page', $menuItem->url_name) }}">{{$menuItem->name}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item mr-2">
                                <a class="nav-link" href="{{ route('login') }}" class="nav-link">
                                    Login
                                </a>
                            </li>
                        @else
                            @can('access-admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('beheer.home') }}" class="nav-link">
                                        Management
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown mr-2">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ auth()->user()->firstname }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item"
                                       href="{{ route('beheer.users.show', auth()->user()) }}">Account overview</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest @guest
                            <li class="nav-item d-flex align-items-center">
                                <a class="btn btn-info text-dark" href="{{ route('front-end.signup') }}">Become a member</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        var navId = $('#scrollNav');
        var timeOut;
        
        $(window).on('load resize', function () {
            var viewPortWidth = $(document).width();
            if (viewPortWidth < 992) {
                navId.addClass('bg-dark');
                $(window).off('scroll')
                window.cancelAnimationFrame(timeOut);
            } else {
                navId.toggleClass('bg-dark', $(window).scrollTop() > 15);
                
                $(window).scroll(function () {
                    if (timeOut) {
                        window.cancelAnimationFrame(timeOut);
                    }
                    
                    timeOut = window.requestAnimationFrame(function () {
                        navId.toggleClass('bg-dark', $(window).scrollTop() > 15);
                    });
                });
            }
        });
    </script>
@endpush

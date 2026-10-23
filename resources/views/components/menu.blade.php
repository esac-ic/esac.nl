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
                            @if($menuItem->children->isNotEmpty())
                                {{--has submenu items so it wil be a dropdown menu--}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="menu-dropdown-{{ $menuItem->id }}"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{$menuItem->name}}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="menu-dropdown-{{ $menuItem->id }}">
                                        @foreach($menuItem->children as $childItem)
                                            <a class="dropdown-item"
                                               href="{{ route('pages.show', $childItem->slug) }}">{{$childItem->name}}</a>
                                        @endforeach
                                    </div>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pages.show', $menuItem->slug) }}">{{$menuItem->name}}</a>
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
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}" class="nav-link">
                                        Admin
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown mr-2">
                                <a class="nav-link dropdown-toggle" href="#" id="account-dropdown"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ auth()->user()->first_name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="account-dropdown">
                                    <a class="dropdown-item"
                                       href="{{ route('admin.members.show', auth()->user()) }}">Account overview</a>
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
                                <a class="btn btn-info text-dark" href="{{ route('signup.create') }}">Become a member</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

@if ($navId)
    @push('scripts')
        <script>
            {
                // On wide screens the menu is transparent over the header until the page scrolls.
                const nav = document.getElementById(@json($navId));
                const updateNav = () => nav.classList.toggle('bg-dark', window.innerWidth < 992 || window.scrollY > 15);

                updateNav();
                window.addEventListener('scroll', updateNav, {passive: true});
                window.addEventListener('resize', updateNav);
            }
        </script>
    @endpush
@endif

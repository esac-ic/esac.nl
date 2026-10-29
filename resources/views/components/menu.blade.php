@props(['transparentAtTop' => false])
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" @if ($transparentAtTop) data-transparent-at-top @endif>
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <x-logo />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-menu"
                aria-controls="site-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="site-menu">
            <ul class="navbar-nav">
                @foreach($pages as $page)
                    @if($page->children->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="menu-dropdown-{{ $page->id }}"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$page->name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="menu-dropdown-{{ $page->id }}">
                                @foreach($page->children as $child)
                                    <a class="dropdown-item"
                                       href="{{ route('pages.show', $child) }}">{{$child->name}}</a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pages.show', $page) }}">{{$page->name}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
            
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item mr-2">
                        <a class="nav-link" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @else
                    @can('access-admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
                               href="{{ route('account.show') }}">Account overview</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </li>
                @endguest @guest
                    <li class="nav-item d-flex align-items-center">
                        <a class="btn btn-accent" href="{{ route('sign-up.create') }}">Become a member</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
    {{ $slot }}
</nav>

<div class="nav-container">
    <div id="{{ $navId }}" class="{{ $navClass }} navbar-dark">

<div class="container">
<nav class="navbar navbar-expand-lg">
  <a class="navbar-brand" href="{{ url('/') }}">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510.24 259" class="logo logo-esac" fill="white">
      <path d="M31.71 169.16h79.52v16.94H61.45v12.61h46.18v16.19H61.45v15.64h51.22v18h-81zM135.59 222.26l31.73-1.46q1 5.69 4.2 8.66 5.14 4.81 14.72 4.82 7.14 0 11-2.46c2.58-1.65 3.86-3.55 3.86-5.72s-1.22-3.89-3.68-5.52-8.14-3.15-17.08-4.6q-21.93-3.63-31.29-9.63T139.64 191q0-6.12 4.82-11.56t14.54-8.53q9.67-3.1 26.54-3.11 20.69 0 31.54 5.66t12.92 18l-31.43 1.35a11.74 11.74 0 0 0-5.27-7.79q-4-2.45-11.08-2.44c-3.87 0-6.8.61-8.76 1.81s-2.94 2.68-2.94 4.42c0 1.26.81 2.4 2.43 3.41s5.3 2 11.19 2.92q21.85 3.47 31.32 7T229.2 211a18 18 0 0 1 4.31 11.74q0 7.63-5.74 14.08t-16.05 9.77q-10.32 3.33-26 3.33-27.54 0-38.14-7.8t-11.99-19.86zM322.38 235.42h-37.87l-5.26 13.1H245.2l40.57-79.36h36.37l40.56 79.36h-34.92zm-6.92-17.16l-11.91-28.53-11.79 28.53zM448.16 216l29.23 6.49a35.91 35.91 0 0 1-9.27 15.11 40.69 40.69 0 0 1-15.72 9.14q-9.39 3.09-23.89 3.09-17.6 0-28.75-3.76a43.16 43.16 0 0 1-19.25-13.23q-8.1-9.47-8.1-24.23 0-19.69 14.24-30.27T427 167.8q20.4 0 32.06 6.07a37.81 37.81 0 0 1 17.34 18.62l-29.45 4.82a16.79 16.79 0 0 0-3.24-5.31 18.66 18.66 0 0 0-6.84-4.33 25.7 25.7 0 0 0-9.06-1.52q-11.34 0-17.37 6.71-4.57 5-4.57 15.62 0 13.2 5.45 18.09t15.31 4.89q9.57 0 14.47-4t7.06-11.46z"/>
      <path d="M256.03 68.87l-5.65 5.65-63.64-42.09L5.55 177.69V169L186.74 22.7l69.29 46.17z"/>
      <path class="cls-1" d="M5.55 177.69L186.74 32.43M186.74 32.43l63.64 42.09M250.38 74.52l5.65-5.65M256.03 68.87L186.74 22.7M186.74 22.7L5.55 169M5.55 169v8.69"/>
      <path d="M294.01 8.51l114.97 115.28-6.16 5.65L294.01 19.6l-50.1 41.2-6.55-4.37 56.65-47.92z"/>
      <path class="cls-1" d="M402.82 129.44l6.16-5.65M243.91 60.8l50.1-41.2M294.01 19.6l108.81 109.84M408.98 123.79L294.01 8.51M294.01 8.51l-56.65 47.92M237.36 56.43l6.55 4.37"/>
      <path d="M504.45 137.45v8.93l-80.07-80.07-40.45 32.36-6.1-6.12 46.55-36.12 80.07 81.02z"/>
      <path class="cls-1" d="M504.45 137.45v8.93M383.93 98.67l40.45-32.36M424.38 66.31l80.07 80.07M377.83 92.55l46.55-36.12M424.38 56.43l80.07 81.02M377.83 92.55l6.1 6.12"/>
    </svg>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="ion-navicon h3 text-white"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <ul class="navbar-nav">
        @php($menu = app()->make(\App\CustomClasses\MenuSingleton::MENUSINGLETON))
        @foreach($menu->getMenuItems() as $menuItem)
            @if(count($menu->getSubMenuItem($menuItem->id)) > 0)
                {{--has submenu items so it wil be a dropdown menu--}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{$menuItem->text->text()}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @foreach($menu->getSubMenuItem($menuItem->id) as $subItem)
                            <a class="dropdown-item" href="{{url('/'. $subItem->urlName)}}">{{$subItem->text->text()}}</a>
                        @endforeach
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/'. $menuItem->urlName)}}">{{$menuItem->text->text()}}</a>
                </li>
            @endif
        @endforeach
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img id="selected_lang" src="{{asset('img/lang_icons/flag-the-netherlands.png')}}">
              </a>
              <div id="other_lang" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a href="#" class="dropdown-item" id="set_lang" data-lang="en"><img class="align-middle" src="{{asset('img/lang_icons/flag-united-kingdom.png')}}"> {{trans('menu.en')}}</a>
              </div>
        </li>
        @if (Auth::guest())
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{ url('/login') }}" class="nav-link">
                Login
                </a>
            </li>
        @else

            @if(Auth::user()->hasBackendRigths())
            <li class="nav-item">
                <a class="nav-link" href="{{ url('beheer/home') }}" class="nav-link">
                {{trans('menu.beheer')}}
                </a>
            </li>
            @endif
            <li class="nav-item dropdown mr-2">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->firstname }}
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{ url('/users/'. Auth::user()->id) . '?back=false'}}">{{ trans('menu.account_overview') }}</a>
                <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
              </div>
            </li>
        @endif
        @if (Auth::guest())
        <li class="nav-item d-flex align-items-center">
            <a class="btn btn-info text-dark" href="/lidworden">{{ trans('menu.become_member') }}</a>
        </li>
        @endif
    </ul>
  </div>
</nav>
</div>
</div>
</div>

@push('scripts')
<script>
var navId = $('#scrollNav');
var timeOut;

$(window).on('load resize', function () {
  var viewPortWidth = $(document).width();
  if(viewPortWidth < 992) {
    navId.addClass('bg-dark');
    $(window).off('scroll')
    window.cancelAnimationFrame(timeOut);
  }
  else {
    navId.toggleClass('bg-dark', $(window).scrollTop() > 15);

    $(window).scroll(function(){
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
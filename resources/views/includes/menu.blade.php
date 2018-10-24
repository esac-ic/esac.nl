<div class="nav-container">
    <div id="{{ $navId }}" class="{{ $navClass }} navbar-dark">

<div class="container">
<nav class="navbar navbar-expand-lg">
  <a class="navbar-brand" href="{{ url('/') }}">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="90 0 472.222 330" width="68" height="35" style="vertical-align:top;fill:#fff" class="logo">
       <defs>
         <clipPath id="a">
           <path d="M0 708.66h510.236V0H0z"/>
         </clipPath>
         <clipPath id="b">
           <path d="M0 708.66h510.236V0H0z"/>
         </clipPath>
         <clipPath id="c">
           <path d="M6.3 663.21h250.48V508.22H6.3z"/>
         </clipPath>
         <clipPath id="d">
           <path d="M0 708.66h510.236V0H0z"/>
         </clipPath>
         <clipPath id="e">
           <path d="M238.11 677.4h171.62V556.47H238.11z"/>
         </clipPath>
         <clipPath id="f">
           <path d="M0 708.66h510.236V0H0z"/>
         </clipPath>
         <clipPath id="g">
           <path d="M378.58 629.48H505.2v-89.95H378.58z"/>
         </clipPath>
       </defs>
       <path d="M-12.09-78.57h676v410h-676z" fill="none"/>
       <text x="13.933" y="357.497" font-size="162.607" font-weight="900" transform="scale(1.09995 .90913)" font-family="Arial Black" stroke-width="1.212">
         <tspan x="13.024" y="356.398">E</tspan>
       </text>
       <g transform="matrix(1.3333 0 0 -1.3333 -14.94 908.205)" clip-path="url(#a)">
         <text x=".75" y="1.02" font-size="150.778" font-weight="900" transform="matrix(1 0 0 -.7353 130.363 438.146)" font-family="Arial Black">
           <tspan x="0 114.59128 234.91212 357.94696" y="0">SAC</tspan>
         </text>
       </g>
       <g transform="matrix(1.3333 0 0 -1.3333 -14.94 908.205)" clip-path="url(#b)">
         <g clip-path="url(#c)">
           <path d="M256.78 617.04l-5.65-5.65-63.64 42.09L6.3 508.22v8.69l181.19 146.3z"/>
         </g>
       </g>
       <path d="M-6.54 230.58L235.046 36.9m0 0L319.9 93.02m0 0l7.533-7.535m0 0l-92.385-61.56m0 0L-6.54 218.992m0 0v11.586" fill="none" stroke="#000" stroke-width="1.333"/>
       <g transform="matrix(1.3333 0 0 -1.3333 -14.94 908.205)" clip-path="url(#d)">
         <g clip-path="url(#e)">
           <path d="M294.76 677.4l114.97-115.28-6.16-5.65-108.81 109.84-50.1-41.2-6.55 4.37z"/>
         </g>
       </g>
       <path d="M523.154 166.245l8.213-7.534M311.274 74.725l66.8-54.934m0 0l145.08 146.454m8.213-7.533L378.073 5.005m0 0L302.54 68.9m0 0l8.733 5.826" fill="none" stroke="#000" stroke-width="1.333"/>
       <g transform="matrix(1.3333 0 0 -1.3333 -14.94 908.205)" clip-path="url(#f)">
         <g clip-path="url(#g)">
           <path d="M505.2 548.46v-8.93l-80.07 80.07-40.45-32.36-6.1 6.12 46.55 36.12z"/>
         </g>
       </g>
       <path d="M658.66 176.925v11.907M497.966 125.22L551.9 82.07m0 0l106.76 106.76m-168.827-71.77L551.9 68.9m0 0l106.76 108.026M489.833 117.06l8.134 8.16" fill="none" stroke="#000" stroke-width="1.333"/>
     </svg> 
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="ion-navicon h3 text-white"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <ul class="navbar-nav">
        @php($menu = app()->make(\App\CustomClasses\MenuSingleton::MENUSINGLETON))
        @foreach($menu->getMenuItems() as $menuItem)
            @if($menuItem->show())
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
            <a class="btn btn-info text-dark" href="/lidworden">Word lid</a>
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
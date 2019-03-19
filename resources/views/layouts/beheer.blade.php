@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/summernote.css")}}">
@endpush

@section('main')

@component('includes.menu')
    @slot('navClass', 'bg-dark position-fixed')
    @slot('navId', '')
@endcomponent

<div class="container-fluid mt-5 mt-lg-0">
    <div class="row">
        <div class="col-lg-2 fixed-left px-0 pt-lg-5 h-lg-100" style="z-index:100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-right h-lg-100">
                <a class="navbar-brand d-lg-none" href="{{ url('beheer/home') }}">Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDashboard" aria-controls="navbarDashboard" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="ion-ios-arrow-down h4"></span>
                </button>

              <div class="collapse navbar-collapse align-self-start" id="navbarDashboard">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0 flex-lg-column">
                    <li class="nav-item active">
                        <span class="nav-link">
                            {{ trans('menu.user') }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users/'. Auth::user()->id) . '?back=false'}}">
                            {{ trans('menu.account_overview') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    @if (Auth::guest())
                        <li class="nav-item">
                            <a href="{{ url('/login') }}" class="nav-link">
                            Login
                            </a>
                        </li>
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Activity_administrator'),Config::get('constants.Content_administrator'),Config::get('constants.Certificate_administrator')))
                            <hr class="my-3">

                            <li class="nav-item active">
                                <span class="nav-link">
                                    {{ trans('menu.beheer') }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('beheer/home') }}">
                                Dashboard
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans("menu.leden")}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ url('users') }}">{{trans("user.current_members")}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                                        <a class="dropdown-item" href="{{ url('users/old_members') }}">{{trans("user.old_members")}}</a>
                                        <a class="dropdown-item" href="{{ url('users/pending_members') }}">{{trans("user.pending_members")}}</a>
                                        <a class="dropdown-item" href="{{ url('rols') }}">{{trans("menu.rols")}}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ url('certificates') }}">{{trans("menu.certificate")}}</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Content_administrator'),Config::get('constants.Activity_administrator')))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans("menu.activities")}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ url('agendaItems') }}">{{trans("menu.agendaItems")}}</a>
                                    <a class="dropdown-item" href="{{ url('applicationForms') }}">{{trans("menu.applicationForms")}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                                        <a class="dropdown-item" href="{{ url('agendaItemCategories') }}">{{trans("menu.agendaItemCategories")}}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Content_administrator')))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans("menu.content")}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ url('pages') }}">{{trans("menu.paginaBeheer")}}</a>
                                    <a class="dropdown-item" href="{{ url('newsItems') }}">{{trans("menu.newsItems")}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                                        <a class="dropdown-item" href="{{ url('frontEndReplacement') }}">{{trans("frontEndReplacement.menuname")}}</a>
                                        <a class="dropdown-item" href="{{ url('books') }}">{{trans("menu.books")}}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                            <li class="nav-item">
                                <a href="{{ url('mailList') }}" class="nav-link">
                                {{trans("MailList.menuname")}}
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
              </div>
            </nav>
        </div>
        <div class="col-lg-10 offset-lg-2">
            <section class="mt-4">
            @yield('content')
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/popper.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/summernote.js")}}" type="text/javascript"></script>
<script type="text/javascript">
    $('#users').DataTable();
    
    $(document).ready(function() {
        $('#content_nl').summernote(summernoteSettings);
        $('#content_en').summernote(summernoteSettings);
    });
</script>
@endpush
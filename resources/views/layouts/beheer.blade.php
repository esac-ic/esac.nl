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
                    @if (Auth::guest())
                        <li class="nav-item">
                            <a href="{{ url('/login') }}" class="nav-link">
                            Login
                            </a>
                        </li>
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Activity_administrator'),Config::get('constants.Content_administrator'),Config::get('constants.Certificate_administrator'),Config::get('constants.NSAC_emergency_info_administrator')))
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
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator'),Config::get('constants.NSAC_emergency_info_administrator')))
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
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                                        <a class="dropdown-item" href="{{ url('certificates') }}">{{trans("menu.certificate")}}</a>
                                    @endif
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
                                    <a class="dropdown-item" href="{{ route('beheer.applicationForms.index') }}">{{trans("menu.applicationForms")}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                                        <a class="dropdown-item" href="{{ url('agendaItemCategories') }}">{{trans("menu.agendaItemCategories")}}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Content_administrator'),Config::get('constants.Activity_administrator')))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans("menu.intro")}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('beheer.intro.packages.index') }}">{{trans("menu.introPackages")}}</a>
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
                                    <a class="dropdown-item" href="{{ url('photoAlbums') }}">{{trans("menu.photoAlbums")}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
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
                            <li class="nav-item">
                                <a href="{{ url('beheer/settings') }}" class="nav-link">
                                {{trans("settings.settings")}}
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
<script src="{{mix("js/vendor/datatables.js")}}"></script>
<script src="{{mix("js/vendor/summernote.js")}}"></script>
<script>
    $('#users').DataTable();
    
    $(document).ready(function() {
        let $lastClick;
        let $contentNl = $('#content_nl');
        let $contentEn = $('#content_en');

        // Keep track of the last clicked element.
        $(document).mousedown(function(e) {
            $lastClick = $(e.target);
        });

        // when '$lastClick == null' on blur, we know it was not caused by a click, but maybe by pressing the tab key
        $(document).mouseup(function() {
            $lastClick = null;
        });

        /**
         * Handle a summernote blur event by deactivating the codeview and triggering the originally clicked element.
         * This element has to be triggered manually as summernote will focus the textfield when codeview is deactivated
         *
         * @param e The blur event
         */
        function handleSNBlur (e) {
            let $sn = $(e.target);

            if ($sn.summernote('codeview.isActivated')) {
                $sn.summernote('codeview.deactivate');

                if ($lastClick != null && !$lastClick.hasClass('btn-codeview')) {
                    $lastClick.trigger('click');
                }
            }
        }

        // Initialise summernote textfields & add event to close the code view when focus is lost. This prevents
        // content from being lost, as it is not saved when code view is active.
        $contentNl.summernote(summernoteSettings).on('summernote.blur.codeview', handleSNBlur);
        $contentEn.summernote(summernoteSettings).on('summernote.blur.codeview', handleSNBlur);
    });
</script>
@endpush

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
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('beheer/home') }}">Dashboard</a>
                    </li>
                    @if (Auth::guest())
                        <li class="nav-item">
                            <a href="{{ url('/login') }}" class="nav-link">
                            Login
                            </a>
                        </li>
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                            <li class="nav-item">
                                <a href="{{ url('users') }}" class="nav-link">
                                {{trans("menu.leden")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        <li class="nav-item">
                                <a href="{{ url('users/old_members') }}" class="nav-link">
                                {{trans("user.old_members")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('users/pending_members') }}" class="nav-link">
                                {{trans("user.pending_members")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Content_administrator')))
                            <li class="nav-item">
                                <a href="{{ url('pages') }}" class="nav-link">
                                {{trans("menu.paginaBeheer")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Content_administrator'),Config::get('constants.Activity_administrator')))
                             <li class="nav-item">
                                <a href="{{ url('newsItems') }}" class="nav-link">
                                {{trans("menu.newsItems")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('agendaItems') }}" class="nav-link">
                                {{trans("menu.agendaItems")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('applicationForms') }}" class="nav-link">
                                    {{trans("menu.applicationForms")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                            <li class="nav-item">
                                <a href="{{ url('certificates') }}" class="nav-link">
                                {{trans("menu.certificate")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                            <li class="nav-item">
                                <a href="{{ url('rols') }}" class="nav-link">
                                {{trans("menu.rols")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('books') }}" class="nav-link">
                                {{trans("menu.books")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('agendaItemCategories') }}" class="nav-link">
                                {{trans("menu.agendaItemCategories")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('frontEndReplacement') }}" class="nav-link">
                                {{trans("frontEndReplacement.menuname")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('mailList') }}" class="nav-link">
                                {{trans("MailList.menuname")}} <span class="ion-ios-arrow-forward align-middle ml-1"></span>
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
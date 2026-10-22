@extends('layouts.master')

@section('main')

@component('includes.menu')
    @slot('navClass', 'bg-dark position-fixed')
    @slot('navId', '')
@endcomponent

<div class="container-fluid mt-5 mt-lg-0">
    <div class="row">
        <div class="col-lg-2 fixed-left px-0 pt-lg-5 h-lg-100" style="z-index:100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-right h-lg-100">
                <a class="navbar-brand d-lg-none" href="{{ route('beheer.home') }}">Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDashboard" aria-controls="navbarDashboard" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="ion-ios-arrow-down h4"></span>
                </button>

              <div class="collapse navbar-collapse align-self-start" id="navbarDashboard">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0 flex-lg-column">
                    <li class="nav-item active">
                        <span class="nav-link">
                            {{ 'User' }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.show', [Auth::user(), 'back' => 'false']) }}">
                            {{ 'Account overview' }}
                        </a>
                    </li>
                    @if (Auth::guest())
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                            Login
                            </a>
                        </li>
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value,\App\Enums\Role::ActivityAdministrator->value,\App\Enums\Role::ContentAdministrator->value,\App\Enums\Role::CertificateAdministrator->value))
                            <hr class="my-3">

                            <li class="nav-item active">
                                <span class="nav-link">
                                    {{ 'Management' }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('beheer.home') }}">
                                Dashboard
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value,\App\Enums\Role::CertificateAdministrator->value))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{'Members'}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('users.index') }}">{{'Current members'}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value))
                                        <a class="dropdown-item" href="{{ route('users.index-old-members') }}">{{'Old members'}}</a>
                                        <a class="dropdown-item" href="{{ route('users.indexPendingMembers') }}">{{'Pending users'}}</a>
                                        <a class="dropdown-item" href="{{ route('rols.index') }}">{{'Roles'}}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('certificates.index') }}">{{'Certificates'}}</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::ContentAdministrator->value,\App\Enums\Role::ActivityAdministrator->value))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{'Activities'}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('agendaItems.index') }}">{{'Events'}}</a>
                                    <a class="dropdown-item" href="{{ route('beheer.applicationForms.index') }}">{{'Application forms'}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value))
                                        <a class="dropdown-item" href="{{ route('agendaItemCategories.index') }}">{{'Event categories'}}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::ContentAdministrator->value))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{'Content'}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('pages.index') }}">{{'Page management'}}</a>
                                    <a class="dropdown-item" href="{{ route('newsItems.index') }}">{{'News items'}}</a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value))
                                        <a class="dropdown-item" href="{{ route('books.index') }}">{{'Books'}}</a>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Enums\Role::Administrator->value))
                            <li class="nav-item">
                                <a href="{{ route('mailList.index') }}" class="nav-link">
                                    {{'Mailing lists'}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user-event-log-entry.index') }}" class="nav-link">
                                    {{'User event log'}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('beheer.settings.index') }}" class="nav-link">
                                    {{'Setting'}}
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

@prepend('scripts')
@vite(['resources/assets/js/vendor/datatables.js', 'resources/assets/js/vendor/summernote.js'])
@endprepend

@push('scripts')
<script type="module">
    $('#users').DataTable();

    $(document).ready(function() {
        let $lastClick;
        let $content = $('#content');

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
        $content.summernote(summernoteSettings).on('summernote.blur.codeview', handleSNBlur);
    });
</script>
@endpush

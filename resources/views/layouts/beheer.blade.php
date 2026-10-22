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
                        <a class="nav-link" href="{{ route('beheer.users.show', [auth()->user(), 'back' => 'false']) }}">
                            {{ 'Account overview' }}
                        </a>
                    </li>
                    @can('access-admin')
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
                    @endcan
                    @can('view-members')
                        <x-nav-dropdown label="Members">
                            <a class="dropdown-item" href="{{ route('beheer.users.index') }}">{{'Current members'}}</a>
                            @can('manage-members')
                                <a class="dropdown-item" href="{{ route('beheer.old-members.index') }}">{{'Old members'}}</a>
                                <a class="dropdown-item" href="{{ route('beheer.pending-users.index') }}">{{'Pending users'}}</a>
                            @endcan
                            @can('manage-roles')
                                <a class="dropdown-item" href="{{ route('beheer.roles.index') }}">{{'Roles'}}</a>
                            @endcan
                            @can('manage-certificates')
                                <a class="dropdown-item" href="{{ route('beheer.certificates.index') }}">{{'Certificates'}}</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-agenda')
                        <x-nav-dropdown label="Activities">
                            <a class="dropdown-item" href="{{ route('beheer.agenda-items.index') }}">{{'Events'}}</a>
                            <a class="dropdown-item" href="{{ route('beheer.application-forms.index') }}">{{'Application forms'}}</a>
                            @can('manage-agenda-categories')
                                <a class="dropdown-item" href="{{ route('beheer.agenda-item-categories.index') }}">{{'Event categories'}}</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-pages')
                        <x-nav-dropdown label="Content">
                            <a class="dropdown-item" href="{{ route('beheer.pages.index') }}">{{'Page management'}}</a>
                            <a class="dropdown-item" href="{{ route('beheer.news-items.index') }}">{{'News items'}}</a>
                            @can('manage-library')
                                <a class="dropdown-item" href="{{ route('beheer.books.index') }}">{{'Books'}}</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-mail-lists')
                        <li class="nav-item">
                            <a href="{{ route('beheer.mail-lists.index') }}" class="nav-link">
                                {{'Mailing lists'}}
                            </a>
                        </li>
                    @endcan
                    @can('view-user-event-log')
                        <li class="nav-item">
                            <a href="{{ route('beheer.user-event-log.index') }}" class="nav-link">
                                {{'User event log'}}
                            </a>
                        </li>
                    @endcan
                    @can('manage-settings')
                        <li class="nav-item">
                            <a href="{{ route('beheer.settings.show') }}" class="nav-link">
                                {{'Setting'}}
                            </a>
                        </li>
                    @endcan
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

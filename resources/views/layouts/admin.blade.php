@extends('layouts.master')

@section('main')

<x-menu nav-class="bg-dark position-fixed" />

<div class="container-fluid mt-5 mt-lg-0">
    <div class="row">
        <div class="col-lg-2 fixed-left px-0 pt-lg-5 h-lg-100" style="z-index:100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-right h-lg-100">
                <a class="navbar-brand d-lg-none" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#admin-menu" aria-controls="admin-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="ion-ios-arrow-down h4"></span>
                </button>

              <div class="collapse navbar-collapse align-self-start" id="admin-menu">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0 flex-lg-column">
                    <li class="nav-item active">
                        <span class="nav-link">
                            User
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('account.show') }}">
                            Account overview
                        </a>
                    </li>
                    <hr class="my-3">

                    <li class="nav-item active">
                        <span class="nav-link">
                            Admin
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        Dashboard
                        </a>
                    </li>
                    @can('view-members')
                        <x-nav-dropdown label="Members">
                            <a class="dropdown-item" href="{{ route('admin.members.index') }}">Current members</a>
                            @can('manage-members')
                                <a class="dropdown-item" href="{{ route('admin.former-members.index') }}">Former members</a>
                                <a class="dropdown-item" href="{{ route('admin.pending-members.index') }}">Pending members</a>
                            @endcan
                            @can('manage-certificates')
                                <a class="dropdown-item" href="{{ route('admin.certificates.index') }}">Certificates</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-events')
                        <x-nav-dropdown label="Events">
                            <a class="dropdown-item" href="{{ route('admin.events.index') }}">Events</a>
                            <a class="dropdown-item" href="{{ route('admin.registration-forms.index') }}">Registration forms</a>
                            @can('manage-event-categories')
                                <a class="dropdown-item" href="{{ route('admin.event-categories.index') }}">Event categories</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-pages')
                        <x-nav-dropdown label="Content">
                            <a class="dropdown-item" href="{{ route('admin.pages.index') }}">Pages</a>
                            <a class="dropdown-item" href="{{ route('admin.news-items.index') }}">News items</a>
                            @can('manage-library')
                                <a class="dropdown-item" href="{{ route('admin.books.index') }}">Books</a>
                            @endcan
                        </x-nav-dropdown>
                    @endcan
                    @can('manage-mail-lists')
                        <li class="nav-item">
                            <a href="{{ route('admin.mail-lists.index') }}" class="nav-link">
                                Mail lists
                            </a>
                        </li>
                    @endcan
                    @can('view-member-log')
                        <li class="nav-item">
                            <a href="{{ route('admin.member-log.index') }}" class="nav-link">
                                Member log
                            </a>
                        </li>
                    @endcan
                    @can('manage-settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.edit') }}" class="nav-link">
                                Settings
                            </a>
                        </li>
                    @endcan
                </ul>
              </div>
            </nav>
        </div>
        <div class="col-lg-10 offset-lg-2">
            <section class="mt-4">
            @include('includes.flash')
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
    $('.js-datatable').DataTable();

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

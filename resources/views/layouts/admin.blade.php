@extends('layouts.master')

@section('main')

<x-menu />

<div class="container-fluid mt-5 mt-lg-0">
    <div class="row">
        <nav class="col-lg-2 admin-sidebar navbar navbar-expand-lg navbar-light" aria-label="Admin">
            <span class="navbar-brand d-lg-none">@yield('title', 'Admin')</span>
            <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-toggle="collapse" data-target="#admin-menu" aria-controls="admin-menu" aria-expanded="false">
                Menu <x-heroicon-o-chevron-down />
            </button>

            <div class="collapse navbar-collapse align-items-start" id="admin-menu">
                <div class="admin-nav">
                    <ul class="list-unstyled mb-0">
                        <x-admin-nav.link route="admin.dashboard">Dashboard</x-admin-nav.link>
                    </ul>
                    @can('view-members')
                        <x-admin-nav.group label="Members">
                            <x-admin-nav.link route="admin.members.index" active="admin.members.*">Current members</x-admin-nav.link>
                            @can('manage-members')
                                <x-admin-nav.link route="admin.pending-members.index" active="admin.pending-members.*">Pending members</x-admin-nav.link>
                                <x-admin-nav.link route="admin.former-members.index" active="admin.former-members.*">Former members</x-admin-nav.link>
                            @endcan
                            @can('manage-certificates')
                                <x-admin-nav.link route="admin.certificates.index" active="admin.certificates.*">Certificates</x-admin-nav.link>
                            @endcan
                        </x-admin-nav.group>
                    @endcan
                    @can('manage-events')
                        <x-admin-nav.group label="Events">
                            <x-admin-nav.link route="admin.events.index" active="admin.events.*">Events</x-admin-nav.link>
                            <x-admin-nav.link route="admin.registration-forms.index" active="admin.registration-forms.*">Registration forms</x-admin-nav.link>
                            @can('manage-event-categories')
                                <x-admin-nav.link route="admin.event-categories.index" active="admin.event-categories.*">Event categories</x-admin-nav.link>
                            @endcan
                        </x-admin-nav.group>
                    @endcan
                    @canany(['manage-pages', 'manage-news', 'manage-library'])
                        <x-admin-nav.group label="Content">
                            @can('manage-pages')
                                <x-admin-nav.link route="admin.pages.index" active="admin.pages.*">Pages</x-admin-nav.link>
                            @endcan
                            @can('manage-news')
                                <x-admin-nav.link route="admin.news-items.index" active="admin.news-items.*">News items</x-admin-nav.link>
                            @endcan
                            @can('manage-library')
                                <x-admin-nav.link route="admin.books.index" active="admin.books.*">Books</x-admin-nav.link>
                            @endcan
                        </x-admin-nav.group>
                    @endcanany
                    @canany(['manage-mail-lists', 'view-member-log', 'manage-settings'])
                        <x-admin-nav.group label="Club">
                            @can('manage-mail-lists')
                                <x-admin-nav.link route="admin.mail-lists.index" active="admin.mail-lists.*">Mail lists</x-admin-nav.link>
                            @endcan
                            @can('view-member-log')
                                <x-admin-nav.link route="admin.member-log.index" active="admin.member-log.*">Member log</x-admin-nav.link>
                            @endcan
                            @can('manage-settings')
                                <x-admin-nav.link route="admin.settings.edit" active="admin.settings.*">Settings</x-admin-nav.link>
                            @endcan
                        </x-admin-nav.group>
                    @endcanany
                </div>
            </div>
        </nav>
        <main class="col-lg-10 offset-lg-2">
            <section class="mt-lg-4">
                @include('includes.flash')
                @yield('content')
            </section>
        </main>
    </div>
</div>
@endsection


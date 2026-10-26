<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="verification" content="6364deb46fa2fe44c96bc77a821e8ab4" />
        <title>@yield('title') - Eindhovense Studenten Alpen Club</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">

        @stack('styles')
        @vite(['resources/assets/scss/theme.scss'])

    </head>
    <body>
        @yield('main')
        <x-confirm-dialog />

        @vite(['resources/assets/js/app.js'])
        @stack('scripts')
    </body>
</html>


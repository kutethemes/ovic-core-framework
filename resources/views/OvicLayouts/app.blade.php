<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    @yield('head')

</head>
<body>
<div id="app">

    <!-- Header -->
    @include('ovic::Oviclayouts.header')

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    @include('ovic::Oviclayouts.footer')

</div>

<!-- Scripts -->
<script src="{{ asset('css/bootstrap.min.js') }}" defer></script>

@yield('footer')

</body>
</html>

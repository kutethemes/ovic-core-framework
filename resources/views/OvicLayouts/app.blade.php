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
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

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

<!-- Mainly scripts -->
<script src="{{ asset('js/jquery-3.1.1.min.js') }}" defer></script>
<script src="{{ asset('js/popper.min.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}" defer></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}" defer></script>

@yield('footer')

</body>
</html>

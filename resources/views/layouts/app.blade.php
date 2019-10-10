<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ ovic_style('app') }}" rel="stylesheet">
    <link href="{{ ovic_style('bootstrap') }}" rel="stylesheet">

    @yield('head')

</head>
<body>
<div id="app">

    <!-- Header -->
    @include('ovic::layouts.header')

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    @include('ovic::layouts.footer')

</div>

<!-- Scripts -->
<script src="{{ ovic_script('app') }}" defer></script>
<script src="{{ ovic_script('bundle') }}" defer></script>
<script src="{{ ovic_script('bootstrap') }}" defer></script>

@yield('footer')

</body>
</html>

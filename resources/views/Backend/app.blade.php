<?php
/**
 * The template for our theme
 *
 * @package Ovic
 * @subpackage Framework
 *
 * @version 1.0
 */
?><!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    @yield('head')

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

</head>

<body class="fixed-sidebar no-skin-config">

<div id="app">

    @include( ovic_blade('Backend.left-sidebar') )

    <div id="page-wrapper" class="gray-bg">

        @include( ovic_blade('Backend.nav-bar') )

        @yield('content')

        @include( ovic_blade('Backend.footer') )

    </div>

    @include( ovic_blade('Backend.right-sidebar') )

</div>

<!-- Mainly scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- Custom and plugin javascript -->
<script src="{{ asset('js/inspinia.js') }}"></script>

@yield('footer')

</body>
</html>

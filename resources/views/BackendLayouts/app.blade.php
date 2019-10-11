<?php
/**
 * The template for our theme
 *
 * @package Laravel
 * @subpackage Laravel
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

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

</head>

<body class="fixed-sidebar no-skin-config full-height-layout">

<div id="wrapper">

    @include( ovic_blade('BackendLayouts.header') )

    <div id="page-wrapper" class="gray-bg">

        @include( ovic_blade('BackendLayouts.nav-bar') )

        <div class="fh-breadcrumb">

            <div class="full-height">

                @yield('content')

            </div>

        </div>

        @include( ovic_blade('BackendLayouts.footer') )

    </div>

    @include( ovic_blade('BackendLayouts.right-sidebar') )

</div>

<!-- Mainly scripts -->
<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}" defer></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}" defer></script>
<!-- Custom and plugin javascript -->
<script src="{{ asset('js/inspinia.js') }}" defer></script>
<script src="{{ asset('js/plugins/pace/pace.min.js') }}" defer></script>

@yield('footer')

</body>
</html>

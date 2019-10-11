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

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

</head>

<body>

<div id="wrapper">

    @include( ovic_blade('Frontend.header') )

    <div id="page-wrapper">

        @include( ovic_blade('Frontend.nav-bar') )

        @yield('content')

        @include( ovic_blade('Frontend.footer') )

    </div>

    @include( ovic_blade('Frontend.right-sidebar') )

</div>

<!-- Mainly scripts -->
<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>

@yield('footer')

</body>
</html>

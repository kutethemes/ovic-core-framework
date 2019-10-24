@php
    /**
     * The template for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield( 'title','Laravel Ovic' )</title>

    {{-- Blade styles --}}
    @stack( 'styles' )
    {{-- Main style --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

    <style>
        .nav > li > a {
            font-weight: 400;
            font-size: 12px;
            padding: 12px 20px 12px 10px;
        }
        .nav-second-level li a {
            padding: 6px 10px 6px 10px;
            padding-left: 20px;
        }
        .nav > li.active {
            border-left-width: 2px;
        }
    </style>

</head>

<body class="fixed-sidebar no-skin-config">

<div id="app">

    @include( ovic_blade('Backend.left-sidebar') )

    <div id="page-wrapper" class="gray-bg">

        @include( ovic_blade('Backend.nav-bar') )

        @yield( 'content' )

        @include( ovic_blade('Backend.footer') )

    </div>

    @include( ovic_blade('Backend.right-sidebar') )

</div>

{{-- Mainly scripts --}}
<script src="{{ asset('js/app.js') }}"></script>
<script defer src="{{ asset('js/plugins/metisMenu/metisMenu.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/slimscroll/slimscroll.min.js') }}"></script>
{{-- Custom and plugin javascript --}}
<script defer src="{{ asset('js/inspinia.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
{{-- Blade scripts --}}
@stack( 'scripts' )

</body>
</html>

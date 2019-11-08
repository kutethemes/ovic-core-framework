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

    <title>@yield( 'title','Dashboard' )</title>

    {{-- Blade styles --}}
    @stack( 'styles' )
    {{-- Main style --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">

    <style>
        .navbar-top-links li {
            position: relative;
        }

        .navbar-default .nav > li > a {
            font-weight: 400;
            font-size: 12px;
            padding: 12px 20px 12px 10px;
        }

        .navbar-default .nav-second-level li a {
            padding: 6px 10px 6px 10px;
            padding-left: 20px;
        }

        .navbar-default .nav > li.active {
            border-left-width: 2px;
        }

        .navbar.navbar-static-top {
            background-color: #fff;
        }

        body.full-height-layout #app {
            height: 100%;
        }

        #app {
            width: 100%;
            overflow-x: hidden;
            display: -ms-flex;
            display: -webkit-flex;
            display: flex;
        }

        .full-height-content {
            height: calc(100% - 85px);
            position: relative;
        }

        .normal-scroll-content {
            overflow-x: hidden;
            height: 100%;
        }

        @media (max-width: 1024px) {
            body.full-height-layout #app {
                display: block;
            }

            .wrapper-content {
                display: block;
                padding-bottom: 60px;
            }

            .wrapper-content > * {
                max-width: inherit;
                float: none;
            }

            .wrapper-content,
            .wrapper-content > *,
            .full-height-layout #wrapper,
            .full-height-layout #page-wrapper {
                height: auto !important;
            }
        }
    </style>

</head>

<body class="fixed-sidebar no-skin-config full-height-layout">

<div id="app">

    @stack( 'before-content' )

    @include( name_blade('Backend.left-sidebar') )

    <div id="page-wrapper" class="gray-bg">

        @include( name_blade('Backend.nav-bar') )

        <div class="row full-height-content wrapper wrapper-content animated fadeInUp">

            @yield( 'content' )

        </div>

        @include( name_blade('Backend.footer') )

    </div>

    @include( name_blade('Backend.right-sidebar') )

    @stack( 'after-content' )

</div>

{{-- Mainly scripts --}}
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/plugins/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/plugins/slimscroll/slimscroll.min.js') }}"></script>
{{-- Custom and plugin javascript --}}
<script src="{{ asset('js/inspinia.min.js') }}"></script>
<script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
{{-- Blade scripts --}}
@stack( 'scripts' )

</body>
</html>

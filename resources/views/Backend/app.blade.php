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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">

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
    {{-- App style --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>

<body class="fixed-sidebar no-skin-config full-height-layout {{ str_replace( '.', '-' ,Route::currentRouteName() ) }}">

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

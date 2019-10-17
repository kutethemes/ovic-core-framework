@php
    /**
     * The account for our theme
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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:400" rel="stylesheet">

@yield('head')

<!-- Styles -->
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
        }
        body {
            background-color: #f4f7f6;
            font-family: "Ubuntu", sans-serif;
            font-size: 14px;
            color: #5A5A5A;
            overflow-x: hidden;
            margin: 0;
        }
        body::before {
            top: 0;
        }
        body::before, body::after {
            height: 5px;
            width: 100%;
            position: fixed;
            content: '';
            z-index: 999;
        }
        a {
            cursor: pointer;
            color: #428bca;
            text-decoration: none;
            background: transparent;
        }
        .vertical-align-wrap {
            position: absolute;
            width: 100%;
            height: 100%;
            display: table;
        }
        #wrapper::before {
            left: 0;
        }
        #wrapper::after {
            right: 0;
        }
        #wrapper::before, #wrapper::after {
            height: 100vh;
            width: 5px;
            position: fixed;
            content: '';
            z-index: 999;
            top: 0;
        }
        .auth-main::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url({{ asset('img/auth_bg.jpg') }}) no-repeat top left fixed;
        }
        .vertical-align-middle {
            display: table-cell;
            vertical-align: middle;
        }
        .auth-main::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 400px;
            height: 100%;
            z-index: -1;
            background: #191f28;
        }
        .auth-box {
            width: 380px;
            height: auto;
            margin-left: 130px;
        }
        .auth-box .top {
            margin-bottom: 30px;
        }
        .card {
            padding: 10px;
            background: #fff;
            transition: .5s;
            border: 0;
            margin-bottom: 30px;
            position: relative;
            width: 100%;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
        }
        .card .header {
            color: #444;
            padding: 20px;
            position: relative;
            box-shadow: none;
        }
        .dev {
            text-align: center;
            font-size: 15px;
            padding-bottom: 25px
        }
        .card .body {
            color: #444;
            padding: 20px;
            font-weight: 400;
        }
        .btn-primary {
            background: #1ab394;
            border-color: #1ab394;
            margin-top: 20px;
            font-size: 14px;
            color: #FFFFFF;
            border-radius: 3px;
            -webkit-appearance: button;
            cursor: pointer;
            text-transform: none;
            overflow: visible;
            padding: 5px 10px;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
        .form-control {
            background-image: none;
            border: 1px solid #e5e6e7;
            border-radius: 1px;
            color: inherit;
            display: block;
            padding: 6px 12px;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            width: 100%;
            box-shadow: none;
            background-color: #fff;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .bottom {
            text-align: center;
            margin-top: 20px;
        }
        .auth-box .helper-text {
            color: #9A9A9A;
            display: block;
        }
        .m-b-10 {
            margin-bottom: 10px;
        }
        .invalid-feedback {
            display: block;
            padding-bottom: 10px;
            font-style: italic;
            font-weight: 300;
            color: red;
        }
    </style>

</head>

<body class="account-page theme-orange">

<div id="wrapper">

    <div class="vertical-align-wrap">
        <div class="vertical-align-middle auth-main">
            <div class="auth-box">

                @yield('content')

            </div>
        </div>
    </div>

</div>

</body>
</html>
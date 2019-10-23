@php
    /**
     * The profile user for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @var $user
     * @version 1.0
     */
    $user_name = ucfirst($user->name);
@endphp

@extends( ovic_blade('Backend.app') )

@section( 'title', $user_name )

@section( 'content' )

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ $user_name }} Profile</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>{{ $user_name }}</strong>
                </li>
            </ol>
        </div>
    </div>

@endsection
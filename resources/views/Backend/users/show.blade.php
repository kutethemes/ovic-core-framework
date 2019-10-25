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



@endsection
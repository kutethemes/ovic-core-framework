@php
    /**
     * The main roles for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade( 'Backend.users.app' ) )

@section( 'title', 'QUẢN LÝ NHÓM NGƯỜI DÙNG' )

@section( 'page-list' )

    @include( ovic_blade('Backend.roles.list') )

@endsection

@section( 'page-edit' )

    @include( ovic_blade('Backend.roles.edit') )

@endsection
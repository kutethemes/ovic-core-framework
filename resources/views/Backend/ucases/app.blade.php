@php
    /**
     * The main ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade( 'Backend.users.app' ) )

@section( 'title', 'PHÂN QUYỀN CHỨC NĂNG' )

@section( 'page-list' )



@endsection

@section( 'page-edit' )

    @include( ovic_blade('Backend.ucases.edit') )

@endsection

@section( 'after-content' )

    @include( ovic_blade('Components.icon.template') )

@endsection
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

@section( 'title', 'PHÂN QUYỀN' )

@push( 'styles' )
    <!-- Sweet Alert -->
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
@endpush

@push( 'scripts' )
    <!-- Sweet alert -->
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@section( 'page-list' )



@endsection

@section( 'page-edit' )



@endsection
@php
    /**
     * The main users for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade('Backend.app') )

@section( 'title', 'Users Manager' )

@push( 'styles' )
    <style>
        .btn-danger {
            float: left;
        }
        .head-group .button-group {
            float: right;
        }
        .client-name,
        .client-email {
            width: 210px;
        }
        .client-options {
            width: 110px;
            text-align: center;
        }
        .client-options > * {
            margin: 0 5px;
        }
        .dataTables_filter {
            text-align: right;
        }
    </style>
@endpush

@section( 'content' )

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>@yield( 'page-title', 'Users' ) Manager</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>@yield( 'page-title', 'Users' ) Manager</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">
                        @yield( 'page-list', \View::make( ovic_blade('Backend.users.list') ) )
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox selected">
                    <div class="ibox-content">
                        @yield( 'page-edit', \View::make( ovic_blade('Backend.users.edit') ) )
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


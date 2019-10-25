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

@section( 'title', 'QUẢN LÝ NGƯỜI DÙNG' )

@push( 'styles' )
    <style>
        .ibox {
            background-color: #fff;
        }
        .btn-danger {
            float: left;
        }
        .head-group .button-group {
            float: right;
        }
        .client-name,
        .client-email {
            width: 230px;
        }
        .client-options {
            width: 110px;
            text-align: center;
        }
        .client-options > * {
            float: left;
        }
        .client-options > * {
            margin: 0 5px;
        }
        .head-table > * {
            text-align: center;
        }
        .dataTables_wrapper {
            position: relative;
        }
        .dataTables_processing.card {
            position: absolute;
            border: none;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.6);
        }
        .sk-spinner-double-bounce.sk-spinner {
            top: calc(50% - 20px);
        }
        .form-group.submit {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            z-index: 3;
            padding: 15px;
        }
        @media (min-width: 1200px) {
            .head-table > * {
                display: inline-block;
                width: 50%;
                vertical-align: middle;
            }

            .dataTables_filter {
                text-align: left;
            }

            .dataTables_info {
                text-align: right;
            }
        }
        @media (max-width: 1200px) {
            .head-table {
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@section( 'content' )

    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-content">
                @yield( 'page-list', view( ovic_blade('Backend.users.list') ) )
            </div>
        </div>
    </div>
    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">
                @yield( 'page-edit', view( ovic_blade('Backend.users.edit') ) )
            </div>
        </div>
    </div>

@endsection

@section( 'after-content' )

    @include( ovic_blade('Backend.media.modal') )

@endsection


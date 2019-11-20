@php
    /**
     * The table template for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@push( 'styles' )
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- style dataTable --}}
    <style>
        .client-avatar {
            max-width: 42px;
        }

        .ibox {
            background-color: #fff;
        }

        .btn-danger {
            float: left;
        }

        .head-group .button-group {
            float: right;
        }

        .client-donvi {
            min-width: 250px;
        }

        .client-name,
        .client-email {
            min-width: 220px;
            max-width: 220px;
        }

        .client-status {
            min-width: 90px;
            max-width: 90px;
            text-align: center;
        }

        td.client-status {
            text-align: right;
        }

        .client-status > * {
            float: none;
        }

        .client-status .inactive .label-warning {
            background-color: #ccc;
        }

        .client-avatar,
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
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .sk-spinner.sk-spinner-wave {
            display: block;
            position: absolute;
            top: calc(50% - 20px);
            left: 0;
            right: 0;
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

        .dataTables_scroll table {
            margin-bottom: 0;
        }

        .dataTables_scrollHead table thead > tr > th {
            border: none;
        }

        .footer-table {
            margin-top: 1rem;
        }

        .clients-list table tr td {
            min-height: 46px;
            word-wrap: break-word;
        }

        .clients-list tbody > tr {
            cursor: pointer;
        }

        .clients-list table tbody > tr.active {
            background-color: #1ab394;
            color: #fff;
        }

        div.client-detail {
            height: auto;
            padding-bottom: 40px;
        }

        .form-group.submit {
            margin-bottom: 0;
            margin-top: 1rem;
            text-align: right;
        }

        div.chosen-container {
            font-size: 0.9rem;
        }

        .form-group label.error {
            width: 100%;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollHead .table {
            width: 100% !important;
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

        @media (max-width: 1400px) {
            .client-detail .form-group {
                display: block;
            }

            .client-detail .form-group > * {
                max-width: inherit;
                flex: none;
            }

            .client-detail .hr-line-dashed {
                margin: 5px 0;
            }
        }
    </style>

    @stack( 'styles.table' )
@endpush

@push( 'scripts' )
    {{-- Sweet alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- dataTables --}}
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    @include( name_blade('Components.table-script') )

    @stack( 'scripts.table' )
@endpush

@section( 'content' )

    @yield( 'content-table' )

@endsection


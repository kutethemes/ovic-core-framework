@php
    /**
     * The table classic template for our theme
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
        .table tbody td.dataTables_empty {
            height: 46px !important;
        }

        .ibox {
            background-color: #fff;
        }

        .ibox .head-group {
            padding: 15px 35px 0 35px;
        }

        .btn-danger {
            float: left;
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

        .dataTables_scroll table.table {
            margin-bottom: 0;
        }

        .footer-table {
            margin-top: 1rem;
        }

        .clients-list table.table tr td {
            min-height: 46px;
            word-wrap: break-word;
        }

        .clients-list tbody > tr {
            cursor: pointer;
        }

        .dataTables_length > * {
            white-space: nowrap;
            font-size: 0;
        }

        .clients-list table.table tbody > tr.active {
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

        .form-group .btn.edit-field {
            height: 100%;
        }

        div.chosen-container {
            font-size: 0.9rem;
        }

        .chosen-container.chosen-container-multi .chosen-choices {
            padding: 0 10px;
        }

        .form-group label.error {
            width: 100%;
        }

        input[type="checkbox"] {
            cursor: pointer;
        }

        .clients-list table.table thead th,
        .clients-list table.table tr td {
            border: 1px solid #aaa;
        }

        .clients-list table.table thead th {
            border-bottom: none;
        }

        .clients-list table.table thead tr {
            background-color: #9e9e9e;
        }

        .clients-list table.table thead tr th {
            color: #fff;
            font-weight: normal;
            border-bottom: none;
        }

        .dataTables_scrollHead .table {
            border-top: 1px solid #aaa;
            width: 100% !important;
        }

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .head-table > * {
            margin-bottom: 15px;
        }

        .head-table > *:not(:last-child) {
            margin-right: 10px;
        }

        .ibox-content {
            background: #f3f3f4;
        }

        .ibox-content .clients-list {
            margin-top: 0;
        }

        .table-filter .input-control {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }

        .client-id {
            min-width: 30px;
            max-width: 30px;
            text-align: center;
        }

        .table-filter > * {
            display: inline-block;
            -webkit-box-flex: 0;
            -webkit-flex: 0 1 auto;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
        }

        .table-filter > .form-group.filter-select {
            -webkit-box-flex: 1;
            -webkit-flex: 1 1 auto;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            text-align: right;
        }

        .table-filter .chosen-container-single .chosen-single {
            line-height: 25px;
        }

        .table-filter .btn.btn-white {
            padding-left: 20px;
            padding-right: 20px;
            line-height: 22px;
            margin-left: 10px;
        }

        .table-filter .btn.btn-white,
        .head-table .btn-group > a.btn.btn-default {
            flex: inherit;
        }

        .table-filter input {
            height: 35px;
        }

        .table-filter .custom-select {
            height: 36px;
        }

        .table-filter input,
        .dataTables_length select,
        .table-filter .btn.btn-white,
        .table-filter .custom-select,
        .head-table .btn-group > a.btn.btn-default,
        .table-filter .chosen-container-single .chosen-single {
            border-radius: 3px;
            border-color: #aaa;
        }

        .table-filter .input-control label {
            vertical-align: bottom;
            margin-bottom: 0;
        }

        .table-filter .form-group button {
            vertical-align: bottom;
        }

        .dataTables_length,
        .dataTables_paginate {
            -webkit-box-flex: 0;
            -webkit-flex: 0 1 auto;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
        }

        .dataTables_paginate {
            -webkit-box-flex: 1;
            -webkit-flex: 1 1 auto;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            text-align: right;
        }

        .dataTables_paginate ul,
        .dataTables_paginate li {
            display: inline-block;
        }

        .head-table .btn-group > a.btn.add-new {
            margin-right: 10px;
            border-radius: 3px;
        }

        .head-table .btn-group > a.btn.add-new:hover {
            color: #fff;
            border-color: #18a689;
        }

        .head-table .btn-group > a.btn.delete-select {
            border-color: #ed5565;;
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .dataTables_filter input {
            border-radius: 50px;
            border-color: #aaa;
            padding-left: 30px;
            height: 100%;
        }

        .dataTables_filter label {
            font-size: 0;
            line-height: 0;
            position: relative;
            margin-bottom: 0;
            height: 100%;
        }

        .dataTables_filter label::before {
            content: "\f002";
            font-size: 16px;
            font-family: FontAwesome;
            position: absolute;
            left: 10px;
            top: calc(50% - 8px);
            bottom: 0;
            font-weight: normal;
            line-height: 16px;
        }

        .table-filte {
            text-align: right;
        }

        .head-table > .dataTables_info {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        ul.pagination > li > a,
        ul.pagination > li > span {
            padding: 8px 15px;
        }

        .client-options {
            text-align: center;
            width: 90px;
            min-width: 90px;
        }

        .client-options .btn {
            margin-bottom: 5px !important;
            padding: 0;
            line-height: 15px;
            float: none;
            width: 30px;
            height: 30px;
        }

        .client-options .btn:not(:last-child) {
            margin-right: 10px !important;
        }

        .client-status {
            width: 80px !important;
            min-width: 80px;
        }

        .modal form {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        .modal-body {
            position: relative;
        }

        .modal .form-group.submit {
            left: 0;
            right: 0;
            background-color: #fff;
            z-index: 3;
            padding: 15px;
        }

        @media (min-width: 1200px) {
            .dataTables_filter {
                text-align: right;
            }

            .dataTables_info {
                text-align: right;
            }
        }

        @media (min-width: 600px) {
            .footer-table,
            .table-filter,
            .head-table {
                display: flex;
            }

            .head-table > * {
                display: inline-block;
                vertical-align: middle;
                -webkit-box-flex: 0;
                -webkit-flex: 0 1 auto;
                -ms-flex: 0 1 auto;
                flex: 0 1 auto;
            }

            .head-table > .dataTables_filter {
                -webkit-box-flex: 1;
                -webkit-flex: 1 1 auto;
                -ms-flex: 1 1 auto;
                flex: 1 1 auto;
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

    @stack( 'styles.table.classic' )
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

    @stack( 'scripts.table.classic' )

@endpush

@section( 'content' )

    @yield( 'content-table-classic' )

@endsection

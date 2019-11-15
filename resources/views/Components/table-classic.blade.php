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

        .dataTables_length > * {
            white-space: nowrap;
            font-size: 0;
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

        input[type="checkbox"] {
            cursor: pointer;
        }

        .clients-list table.dataTable thead th,
        .clients-list table.dataTable tr td {
            border: 1px solid #aaa;
        }

        .clients-list table.dataTable thead tr {
            background-color: #9e9e9e;
        }

        .clients-list table.dataTable thead tr th {
            color: #fff;
            font-weight: normal;
            border-bottom: none;
        }

        .dataTables_scrollHead .table {
            border-top: 1px solid #aaa;
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
        }

        .client-id {
            width: 30px;
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

        .dataTables_length select,
        .table-filter .btn.btn-white,
        .head-table .btn-group > a.btn.btn-default,
        .table-filter .chosen-container-single .chosen-single {
            border-radius: 5px;
            border-color: #aaa;
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

        .head-table .btn-group > a.btn.add-new:hover {
            color: #fff;
            border-color: #18a689;
        }

        .head-table .btn-group > a.btn.delete-select {
            margin-left: 10px;
            border-color: #ed5565;;
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
            padding: 6px 12px;
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
            left: 30px;
            right: 30px;
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
    {{-- script dataTable --}}
    <script>

        var OvicTable = null;

        toastr.options = {
            "preventDuplicates": true,
        };

        if ( !$.fn.serializeObject ) {
            $.fn.serializeObject = function () {
                var o = {};
                var a = this.serializeArray();
                $.each( a, function () {
                    if ( o[this.name] ) {
                        if ( !o[this.name].push ) {
                            o[this.name] = [ o[this.name] ];
                        }
                        o[this.name].push( this.value || '' );
                    } else {
                        o[this.name] = this.value || '';
                    }
                } );
                return o;
            };
        }
        /* Init OvicTable */
        $.fn.init_dataTable = function ( main_url, config ) {
            let table = $( this ),
                options = {};

            options = $.extend( {
                processing: true,
                serverSide: true,
                responsive: true,
                dom: '<"head-table"Bif>rt<"footer-table"lp><"clear">',
                buttons: [
                    {
                        text: 'Thêm mới',
                        className: 'btn btn-primary add-new',
                    },
                    {
                        text: '<i class="fa fa-trash"></i> Xóa',
                        className: 'btn btn-danger delete-select disabled',
                        titleAttr: 'Xóa tất cả mục đã chọn.',
                        action: function ( e, dt, node, config ) {
                            let ids = [],
                                data = [],
                                items = table.find( '.select-items' );

                            data.id = 0;
                            data.name = 'Các mục đã chọn';
                            items.each( function ( index, value ) {
                                if ( $( this ).is( ':checked' ) ) {
                                    ids.push(
                                        $( this ).val()
                                    );
                                }
                            } );
                            data.id = ids;

                            $( this ).remove_post( main_url, data );

                            return false;
                        }
                    }
                ],
                ajax: {
                    url: main_url + "/create",
                    dataType: "json",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    data: function ( data ) {
                        let sorting_value = '',
                            filter_value = '',
                            button = $( '.btn-group.sorting .btn-primary' ),
                            filter = $( '.table-filter .select' );

                        if ( button.length ) {
                            sorting_value = button.val();
                        }
                        if ( filter.length ) {
                            filter_value = filter.val();
                        }
                        data.sorting = sorting_value;
                        data.filter = filter_value;
                    },
                    error: function () {
                        swal( {
                            type: 'error',
                            title: "Error!",
                            text: "Không tải được dữ liệu.",
                            showConfirmButton: true
                        } );
                    },
                },
                // scrollX: true,
                createdRow: function ( row, data, dataIndex ) {
                    // Set the data-status attribute, and add a class
                    $( row ).addClass( 'row-' + data.id );
                },
                language: {
                    url: "{{ asset('datatable_language/vi.json') }}"
                }
            }, config );

            OvicTable = table.DataTable( options );
        };

        /* lọc bảng */
        $( document ).on( 'click', '.table-filter button', function () {
            let button = $( this ),
                wrapper = button.closest( '.table-filter' ),
                select = wrapper.find( 'select' ),
                value = select.val();

            OvicTable.column( 2 ).search( value ).draw();
        } );
        /* sắp xếp bảng */
        $( document ).on( 'click', '.btn-group.sorting button', function () {
            let button = $( this ),
                value = button.val();

            if ( !button.hasClass( 'btn-primary' ) ) {
                OvicTable.column( 1 ).search( value ).draw();
                button.toggleClass( 'btn-primary btn-white' );
                $( '.btn-group.sorting button' ).not( button ).removeClass( 'btn-primary' ).addClass( 'btn-white' );
            } else {
                button.toggleClass( 'btn-primary btn-white' );
                OvicTable.column( 1 ).search( '' ).draw();
            }
        } );
        /* chọn item */
        $( document ).on( 'change_select_all', '#select-all', function ( e ) {
            var self = $( this );
            if ( self.is( ':checked' ) ) {
                $( '.btn.delete-select' ).removeClass( 'disabled' );
            } else {
                $( '.btn.delete-select' ).addClass( 'disabled' );
            }
        } );
        $( document ).on( 'change', '#select-all', function ( e ) {
            var self = $( this );

            var item_id = $( '.select-items' );

            if ( self.is( ':checked' ) ) {
                item_id.each( function ( key, value ) {
                    var item = $( value );
                    item.prop( 'checked', 'checked' );
                } );
            } else {
                item_id.each( function ( key, value ) {
                    $( value ).prop( 'checked', '' );
                } );
            }
            self.trigger( 'change_select_all' );
        } );
        $( document ).on( 'change', '.select-items', function ( e ) {
            var check = false,
                all = $( '#select-all' ),
                item_id = $( '.select-items' );

            if ( !all.is( ':checked' ) ) {
                all.prop( 'checked', 'checked' ).trigger( 'change_select_all' );
            } else {
                item_id.each( function ( key, value ) {
                    if ( $( this ).is( ':checked' ) ) {
                        check = true;
                    }
                } );
                if ( check == false ) {
                    all.prop( 'checked', '' ).trigger( 'change_select_all' );
                }
            }
        } );

        @if( user_can('add', $permission) )
        /* Add Post */
        $.fn.add_new = function ( main_url, data ) {
            $.ajax( {
                url: main_url,
                type: 'POST',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {

                    if ( response.status === 200 ) {

                        OvicTable.ajax.reload( null, false );

                        toastr.info( response.message );
                    } else {

                        let html = '';
                        $.each( response.message, function ( index, value ) {
                            html += "<p class='text-danger'>" + value + "</p>";
                        } );

                        swal( {
                            html: true,
                            type: 'error',
                            title: '',
                            text: html,
                            showConfirmButton: true
                        } );
                    }
                },
            } );
        };
        @endif

        @if( user_can('delete', $permission) )
        /* Remove Post */
        $.fn.remove_post = function ( main_url, data ) {
            swal( {
                title: "Bạn có chắc muốn xóa \"" + data.name + "\"?",
                text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Đồng ý xóa!",
                closeOnConfirm: false
            }, function ( isConfirm ) {
                if ( isConfirm ) {

                    $.ajax( {
                        url: main_url + "/" + data.id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        success: function ( response ) {

                            if ( response.status === 'success' ) {
                                OvicTable.ajax.reload( null, false );
                            }

                            swal( {
                                type: response.status,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                            } );

                            $( '.btn-primary.add-new' ).trigger( 'click' );
                        },
                    } );

                }
            } );
        };
        @endif

        @if( user_can('edit', $permission) )
        /* Update Post */
        $.fn.update_post = function ( main_url, data, table ) {

            let tr = $( table ).find( '.row-' + data.id );

            $.ajax( {
                url: main_url + "/" + data.id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {
                    if ( response.status === 200 ) {

                        if ( data.dataTable === undefined ) {
                            OvicTable.ajax.reload( null, false );
                        } else if ( $.isPlainObject( response.data ) ) {
                            OvicTable.row( tr ).data( response.data );
                        }

                        toastr.info( response.message );
                    } else {
                        let html = '';
                        $.each( response.message, function ( index, value ) {
                            html += "<p class='text-danger'>" + value + "</p>";
                        } );

                        swal( {
                            html: true,
                            type: 'error',
                            title: '',
                            text: html,
                            showConfirmButton: true
                        } );
                    }
                },
            } );
        };
        /* Update status */
        $.fn.update_status = function ( main_url, messageOff, messageOn ) {
            let button = $( this ),
                tr = button.closest( 'tr' ),
                data = OvicTable.row( tr ).data(),
                message = messageOff;

            if ( data.status !== 1 ) {
                data.status = 1;
                message = messageOn;
            } else {
                data.status = 0;
            }

            $.ajax( {
                url: main_url + "/" + data.id,
                type: 'PUT',
                dataType: 'json',
                data: {
                    status: data.status,
                    dataTable: true
                },
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {

                    if ( response.status === 200 ) {

                        if ( data.dataTable === undefined ) {
                            OvicTable.ajax.reload( null, false );
                        } else if ( $.isPlainObject( response.data ) ) {
                            OvicTable.row( tr ).data( response.data );
                        }

                        toastr.info( message );

                    } else {
                        let html = '';
                        $.each( response.message, function ( index, value ) {
                            html += "<p class='text-danger'>" + value + "</p>";
                        } );

                        swal( {
                            html: true,
                            type: 'error',
                            title: '',
                            text: html,
                            showConfirmButton: true
                        } );
                    }
                },
            } );
        };
        @endif
    </script>

    @stack( 'scripts.table.classic' )
@endpush

@section( 'content' )

    @yield( 'content-table-classic' )

@endsection

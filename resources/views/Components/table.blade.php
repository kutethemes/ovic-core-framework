@php
    /**
     * The table for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade('Backend.app') )

@push( 'styles' )
    <!-- Sweet Alert -->
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

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
    <!-- Sweet alert -->
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Toastr script -->
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <!-- dataTables -->
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>

        var OvicTable = null;

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
                lengthChange: false,
                serverSide: true,
                dom: '<"head-table"fi>rt<"footer-table"p><"clear">',
                ajax: {
                    url: main_url + "/list",
                    dataType: "json",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    data: function ( data ) {
                        let value = '',
                            button = $( '.btn-group.sorting .btn-primary' );
                        if ( button.length ) {
                            value = button.val();
                        }
                        data.sorting = value;
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
                scrollX: true,
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

                        if ( reload ) {
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
        $( document ).on( 'keyup', '#edit-post input, #edit-post select, #edit-post textarea', function ( event ) {
            let form = $( this ).closest( 'form' ),
                update = form.find( '.update-post' ),
                add = form.find( '.add-post' );

            if ( event.keyCode === 13 ) {

                if ( update.is( ":visible" ) ) {
                    update.trigger( 'click' );
                } else {
                    add.trigger( 'click' );
                }

                event.preventDefault();
            }
        } );
    </script>

    @stack( 'scripts.table' )
@endpush

@section( 'content' )

    @yield( 'content-table' )

@endsection


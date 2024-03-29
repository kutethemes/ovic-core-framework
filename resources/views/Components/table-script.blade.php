@php
    /**
     * The table script template for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

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
    $.fn.init_dataTable = function ( main_url, config, prefix = 'create' ) {
        let table   = $( this ),
            options = {};

        options = $.extend( {
            processing: true,
            serverSide: true,
            responsive: true,
            dom: '<"head-table"fi>rt<"footer-table"p><"clear">',
            ajax: {
                url: main_url + "/" + prefix,
                dataType: "json",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                data: function ( data ) {
                    let sorting_value = '',
                        button        = $( '.btn-group.sorting .btn-primary' );

                    if ( button.length ) {
                        sorting_value = button.val();
                    }
                    data.sorting = sorting_value;
                    data.filter  = $( 'form.table-filter' ).serializeObject();
                },
                complete: function ( response ) {
                    table.trigger( 'dataTable_ajax_complete', [ response ] );
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
    /* lọc bảng */
    $( document ).on( 'click', 'form.table-filter button', function () {
        if ( OvicTable !== null ) {
            OvicTable.ajax.reload( null, false );
        }
    } );
    /* sắp xếp bảng */
    $( document ).on( 'click', '.btn-group.sorting button', function () {
        let button = $( this ),
            value  = button.val();

        if ( !button.hasClass( 'btn-primary' ) ) {
            if ( OvicTable !== null ) {
                OvicTable.column( 1 ).search( value ).draw();
            }
            button.toggleClass( 'btn-primary btn-white' );
            $( '.btn-group.sorting button' ).not( button ).removeClass( 'btn-primary' ).addClass( 'btn-white' );
        } else {
            button.toggleClass( 'btn-primary btn-white' );
            if ( OvicTable !== null ) {
                OvicTable.column( 1 ).search( '' ).draw();
            }
        }
    } );
    /* chọn item */
    $( document ).on( 'change_select_all', '#select-all,.select-all', function ( e ) {
        let self      = $( this ),
            table     = self.closest( '.dataTables_wrapper' ),
            btnDelete = table.find( '.head-table .btn.delete-select' ),
            btnChange = table.find( '.head-table .btn.change-password' );

        if ( self.is( ':checked' ) ) {
            btnDelete.removeClass( 'disabled' );
            btnChange.removeClass( 'disabled' );
        } else {
            btnDelete.addClass( 'disabled' );
            btnChange.addClass( 'disabled' );
        }
    } );
    /* chọn tất cả */
    $( document ).on( 'change', '#select-all,.select-all', function ( e ) {
        let self    = $( this ),
            table   = self.closest( '.dataTables_wrapper' ),
            item_id = table.find( 'tbody .select-items' );

        if ( self.is( ':checked' ) ) {
            item_id.each( function ( key, value ) {
                let item = $( value );
                item.prop( 'checked', 'checked' );
            } );
        } else {
            item_id.each( function ( key, value ) {
                $( value ).prop( 'checked', '' );
            } );
        }
        self.trigger( 'change_select_all' );
    } );
    /* chọn từng item */
    $( document ).on( 'change', '.select-items', function ( e ) {
        var check   = false,
            table   = $( this ).closest( '.dataTables_wrapper' ),
            all     = table.find( 'thead #select-all,thead .select-all' ),
            item_id = table.find( 'tbody .select-items' );

        if ( !all.is( ':checked' ) ) {
            all.prop( 'checked', 'checked' ).trigger( 'change_select_all' );
        } else {
            item_id.each( function ( key, value ) {
                if ( $( this ).is( ':checked' ) ) {
                    check = true;
                }
            } );
            if ( check === false ) {
                all.prop( 'checked', '' ).trigger( 'change_select_all' );
            }
        }
    } );

    @if( user_can('add', $permission) )
    /* Add Post */
    $.fn.add_new = function ( main_url, data ) {

        let button = $( this );

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

                    button.trigger( 'add_post_success', [ response ] );

                    if ( OvicTable !== null ) {
                        OvicTable.ajax.reload( null, false );
                    }

                    toastr.info( response.message );

                } else if ( response.status === 400 ) {

                    button.trigger( 'add_post_error', [ response ] );

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

                } else {
                    button.trigger( 'add_post_action', [ response ] );
                }
            },
            error: function ( response ) {

                button.trigger( 'add_post_error', [ response ] );

                swal( {
                    type: 'error',
                    title: "Error!",
                    text: "Hệ thống không phản hồi.",
                    showConfirmButton: true
                } );
            },
        } );
    };
    @endif

    @if( user_can('delete', $permission) )
    /* Remove Post */
    $.fn.remove_post = function ( main_url, data ) {

        let button = $( this );

        swal( {
            title: "Bạn có chắc muốn xóa \"" + data.name + "\"?",
            text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy",
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
                    data: data,
                    success: function ( response ) {

                        if ( response.status === 'success' ) {

                            button.trigger( 'remove_post_success', [ response ] );

                            if ( OvicTable !== null ) {
                                OvicTable.ajax.reload( null, false );
                            }

                        } else {

                            button.trigger( 'remove_post_error', [ response ] );

                        }

                        swal( {
                            type: response.status,
                            title: response.title,
                            text: response.message,
                            showConfirmButton: true,
                        } );

                        $( '#select-all' ).prop( 'checked', '' ).trigger( 'change_select_all' );
                    },
                    error: function ( response ) {

                        button.trigger( 'remove_post_error', [ response ] );

                        swal( {
                            type: 'error',
                            title: "Error!",
                            text: "Hệ thống không phản hồi.",
                            showConfirmButton: true
                        } );
                    },
                } );

            }
        } );
    };
    @endif

    @if( user_can('edit', $permission) )
    /* Update Post */
    $.fn.update_post = function ( main_url, data, reload = false ) {

        let button = $( this ),
            tr     = $( "#table-posts" ).find( '.row-' + data.id );

        if ( reload ) {
            data.dataTable = true;
        }

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

                    button.trigger( 'update_post_success', [ response ] );

                    if ( OvicTable !== null ) {
                        if ( data.dataTable === undefined ) {
                            OvicTable.ajax.reload( null, false );
                        } else if ( $.isPlainObject( response.data ) ) {
                            OvicTable.row( tr ).data( response.data );
                        }
                    }

                    toastr.info( response.message );

                } else if ( response.status === 400 ) {

                    button.trigger( 'update_post_error', [ response ] );

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
                } else {
                    button.trigger( 'add_post_action', [ response ] );
                }
            },
            error: function ( response ) {

                button.trigger( 'update_post_error', [ response ] );

                swal( {
                    type: 'error',
                    title: "Error!",
                    text: "Hệ thống không phản hồi.",
                    showConfirmButton: true
                } );
            },
        } );
    };
    /* Update status */
    $.fn.update_status = function ( main_url, messageOff, messageOn, reload = false ) {

        let config  = {},
            button  = $( this ),
            tr      = button.closest( 'tr' ),
            data    = (OvicTable !== null) ? OvicTable.row( tr ).data() : [],
            message = messageOff;

        if ( parseInt( data.status ) !== 1 ) {
            data.status = 1;
            message     = messageOn;
        } else {
            data.status = 0;
        }

        config.status = data.status;
        if ( reload === true ) {
            config.dataTable = true;
        }

        $.ajax( {
            url: main_url + "/" + data.id,
            type: 'PUT',
            dataType: 'json',
            data: config,
            headers: {
                'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
            },
            success: function ( response ) {

                if ( response.status === 200 ) {

                    button.trigger( 'update_status_success', [ response ] );

                    if ( OvicTable !== null ) {
                        if ( config.dataTable === undefined ) {
                            OvicTable.ajax.reload( null, false );
                        } else if ( $.isPlainObject( response.data ) ) {
                            OvicTable.row( tr ).data( response.data );
                        }
                    }

                    toastr.info( message );

                } else if ( response.status === 400 ) {

                    button.trigger( 'update_status_error', [ response ] );

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
                } else {
                    button.trigger( 'add_post_action', [ response ] );
                }
            },
            error: function ( response ) {

                button.trigger( 'update_status_error', [ response ] );

                swal( {
                    type: 'error',
                    title: "Error!",
                    text: "Hệ thống không phản hồi.",
                    showConfirmButton: true
                } );
            },
        } );
    };
    @endif
</script>

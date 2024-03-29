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

@extends( name_blade('Components.table-classic') )

@section( 'title', 'QUẢN LÝ NGƯỜI DÙNG' )

@push( 'styles.table.classic' )
    {{-- Chosen --}}
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    {{-- style users --}}
    <style>
        #modal-edit-post .modal-body {
            height: calc(100% - 54px);
        }

        .client-avatar img {
            max-width: 28px;
        }

        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }

        .field-password .input-group-append {
            display: none;
        }

        .table-filter {
            border-bottom: 1px solid #e7eaec;
        }

        .table-filter .input-control div.chosen-container {
            min-width: 320px;
        }

        .client-avatar {
            min-width: 40px;
        }

        .client-email {
            min-width: 250px;
        }

        .client-name {
            min-width: 200px;
        }

        .client-donvi {
            min-width: 230px;
            width: 100%;
        }
    </style>
@endpush

@section( 'content-table-classic' )

    <div class="col-sm-12 full-height hide-sidebar">
        <div class="ibox normal-scroll-content">

            @include( name_blade('Backend.users-classic.list') )

        </div>
    </div>

@endsection

@push( 'after-content' )

    @if( user_can('add', $permission) || user_can('edit', $permission) )

        @include( name_blade('Backend.media.modal') )

        @include( name_blade('Components.modal'), [
           'title'      => 'Edit',
           'id'         => 'modal-edit-post',
           'modal_size' => '500',
           'content'    => name_blade('Backend.users-classic.edit'),
        ])

    @endif

@endpush

@push( 'scripts.table.classic' )
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{-- Jquery Validate --}}
    <script src="{{ asset('js/plugins/validate/jquery.validate.min.js') }}"></script>
    {{-- script users --}}
    <script type="application/javascript">
        let buttonTable = [],
            ajaxurl     = "users-classic";

        @if( user_can('add', $permission) )
        buttonTable.push(
            {
                text: 'Thêm mới',
                className: 'btn btn-primary add-new',
            }
        );
        @endif

        @if( user_can('delete', $permission) )
        buttonTable.push(
            {
                text: '<i class="fa fa-trash"></i> Xóa',
                className: 'btn btn-danger delete-select disabled',
                titleAttr: 'Xóa tất cả mục đã chọn.',
                action: function ( e, dt, node, config ) {
                    let ids   = [],
                        data  = [],
                        body  = $( dt.table().body() ),
                        items = body.find( '.select-items' );

                    data.id   = 0;
                    data.name = 'Các mục đã chọn';
                    items.each( function ( index, value ) {
                        if ( $( this ).is( ':checked' ) ) {
                            ids.push(
                                $( this ).val()
                            );
                        }
                    } );
                    data.id = ids;

                    $( this ).remove_post( ajaxurl, data );

                    return false;
                }
            }
        );
        @endif

        $( "#edit-post" ).validate( {
            errorPlacement: function ( error, element ) {
                element.before( error );
            },
            rules: {
                password_confirmation: {
                    equalTo: "#password"
                }
            }
        } );

        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Không tìm thấy kết quả!",
            disable_search_threshold: 5,
            allow_single_deselect: true
        } );

        $( '.form-group.donvi .chosen-select' ).bind( 'chosen:hiding_dropdown', function () {
            let data   = $( this ).val(),
                phamvi = $( this ).closest( 'form' ).find( '.form-group.phamvi .chosen-select' );

            phamvi.find( 'option' ).hide();

            if ( data !== undefined && data !== null ) {
                $.ajax( {
                    url: ajaxurl + '/create',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        selected: data
                    },
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    success: function ( response ) {

                        $.each( response, function ( index, value ) {

                            if ( data !== parseInt( value ) ) {
                                let option = phamvi.find( 'option[value="' + value + '"]' );

                                if ( option.length ) {
                                    option.show();
                                }
                            }

                        } );
                        phamvi.trigger( 'chosen:updated' );

                    },
                    error: function () {

                        swal( {
                            type: 'error',
                            title: "Error!",
                            text: "Hệ thống không phản hồi.",
                            showConfirmButton: true
                        } );
                    },
                } );
            }
            phamvi.trigger( 'chosen:updated' );
        } );

        $( '#table-posts' ).init_dataTable( ajaxurl, {
            dom: '<"head-table"Bf>rt<"footer-table"lp><"clear">',
            buttons: {
                dom: {
                    button: {
                        className: ''
                    }
                },
                buttons: buttonTable
            },
            columns: [
                {
                    className: "client-id",
                    data: "id",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return '<input id="select-' + data + '" class="select-items" type="checkbox" name="ids[]" value="' + data + '">';
                    }
                },
                {
                    className: "client-avatar",
                    data: "avatar_url",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return "<img alt='Ảnh đại diện' src='" + data + "'>";
                    }
                },
                {
                    className: "client-email",
                    data: "email",
                    sortable: false
                },
                {
                    className: "client-name",
                    data: "name",
                    sortable: false
                },
                {
                    className: "client-donvi",
                    data: "donvi_text",
                    sortable: false
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Người dùng không kích hoạt";
                        let _icon  = "<span class='label label-danger'>Tắt</span>";

                        data = parseInt( data ) === 3 ? 1 : data;

                        switch ( data ) {
                            case 1:
                                _class = "active";
                                _title = "Người dùng đang kích hoạt";
                                _icon  = "<span class='label label-warning'>Bật</span>";
                                break;
                            case 2:
                                _class = "inactive";
                                _title = "Người dùng ẩn";
                                _icon  = "<span class='label label-warning'>Ẩn</span>";
                                break;
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                },
                {
                    className: "client-options",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let html = '';

                        @if( user_can('edit', $permission) )
                            html += '<button class="btn btn-info edit" type="button"><i class="fa fa-edit"></i></button>';
                        @endif
                                @if( user_can('delete', $permission) )
                            html += '<button class="btn btn-danger delete" type="button"><i class="fa fa-trash-o"></i></button>';
                        @endif

                            return html;
                    }
                },
            ]
        } );

        $( document ).on( 'click', '#table-posts .btn.edit', function () {
            let button = $( this ),
                row    = button.closest( 'tr' ),
                form   = $( '#edit-post' ),
                modal  = $( '#modal-edit-post' ),
                user   = OvicTable.row( row ).data(),
                chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ];

            /* active */
            form.find( '.ovic-field-image img' ).attr( 'src', user.avatar_url );

            $.each( user, function ( index, value ) {
                if ( form.find( '[name="' + index + '"]' ).length ) {
                    if ( index === 'status' && parseInt( value ) === 3 ) {
                        value = 1;
                    }
                    if ( chosen.indexOf( index ) !== -1 ) {

                        if ( Array.isArray( value ) ) {
                            value = value.map( Number );
                        }

                        form.find( '[name="' + index + '"]' ).val( value ).trigger( 'chosen:updated' );

                    } else if ( index === 'password' ) {

                        form.find( '[name="' + index + '"]' ).val( value ).attr( 'disabled', 'disabled' ).removeAttr( 'name' ).trigger( 'change' );
                        form.find( '[name="password_confirmation"]' ).removeAttr( 'name' );

                    } else {

                        form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );

                    }
                }
            } );

            modal.find( '.modal-title' ).text( user.name );
            form.find( '.form-group .add-post' ).addClass( 'd-none' );
            form.find( '.field-password-confirmation' ).css( 'display', 'none' );
            form.find( '.field-password .input-group-append' ).css( 'display', 'block' );
            form.find( '.form-group .edit-post,.form-group .delete-post' ).removeClass( 'd-none' );

            modal.modal( {
                backdrop: 'static',
                keyboard: false,
            } );
        } );

        @if( user_can('add', $permission) )
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form  = $( '#edit-post' ),
                modal = $( '#modal-edit-post' );

            form.find( '.ovic-field-image .ovic-image-remove' ).trigger( 'click' );
            form.find( '.field-password input' ).removeAttr( 'disabled' ).attr( 'name', 'password' );
            form.find( '.chosen-select' ).val( '' ).trigger( 'chosen:updated' );
            form.find( '.field-password-confirmation' ).css( 'display', 'block' ).find( 'input' ).attr( 'name', 'password_confirmation' );
            form.find( '.field-password .input-group-append' ).css( 'display', 'none' );

            form.trigger( 'reset' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            modal.find( '.modal-title' ).text( 'Thêm mới' );
            modal.modal( {
                backdrop: 'static',
                keyboard: false,
            } );

            return false;
        } );
        /* Add post */
        $( document ).on( 'click', '#edit-post .btn.add-post', function () {
            let button = $( this ),
                form   = $( '#edit-post' ),
                data   = form.serializeObject();

            button.add_new( ajaxurl, data );

            return false;
        } );
        $( document ).on( 'add_post_success', function ( event, response ) {
            $( '#modal-edit-post' ).modal( 'hide' );
        } );
        @endif

        @if( user_can('edit', $permission) )
        $( document ).on( 'click', 'button.edit-field', function () {
            let group = $( this ).closest( '.input-group' );
            let input = group.find( 'input' );

            if ( input.attr( 'disabled' ) === undefined ) {
                input.attr( 'disabled', 'disabled' ).removeAttr( 'name' );
            } else {
                input.removeAttr( 'disabled' ).attr( 'name', 'password' );
            }
        } );
        /* Update post */
        $( document ).on( 'click', '#edit-post .btn.edit-post', function () {
            let button = $( this ),
                form   = $( '#edit-post' ),
                data   = form.serializeObject();

            button.update_post( ajaxurl, data, true );

            return false;
        } );
        $( document ).on( 'update_post_success', function ( event, response ) {
            $( '#modal-edit-post' ).modal( 'hide' );
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status( ajaxurl,
                "Tắt kích hoạt thành công",
                "Kích hoạt thành công",
                true
            );

            return false;
        } );
        @endif

        @if( user_can('delete', $permission) )
        /* Remove post */
        $( document ).on( 'click', '#edit-post .btn.delete-post', function () {
            let button = $( this ),
                form   = $( '#edit-post' ),
                data   = form.serializeObject();

            button.remove_post( ajaxurl, data );

            return false;
        } );
        $( document ).on( 'click', '#table-posts .btn.delete', function () {
            let button = $( this ),
                row    = button.closest( 'tr' ),
                data   = OvicTable.row( row ).data();

            button.remove_post( ajaxurl, data );

            return false;
        } );
        @endif
    </script>
@endpush


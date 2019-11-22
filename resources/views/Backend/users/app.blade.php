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

@extends( name_blade('Components.table') )

@section( 'title', 'QUẢN LÝ NGƯỜI DÙNG' )

@push( 'styles.table' )
    {{-- Chosen --}}
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    {{-- style users --}}
    <style>
        @if( empty( $permission[0] ) || $permission[0] == false )
            .btn.add-new {
            display: none !important;
        }

        @endif

        .client-avatar img {
            max-width: 28px;
        }

        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }

        .field-password .input-group-append {
            display: none;
        }
    </style>
@endpush

@push( 'scripts.table' )
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{-- Jquery Validate --}}
    <script src="{{ asset('js/plugins/validate/jquery.validate.min.js') }}"></script>
    {{-- script users --}}
    <script>
        var donvidata = JSON.parse(
            JSON.stringify( @json( $donvis ) )
        );

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
        $( document ).on( 'chosen:updated', '.form-group.donvi .chosen-select', function () {
            let data = $( this ).val(),
                phamvi = $( this ).closest( 'form' ).find( '.form-group.phamvi .chosen-select' );

            phamvi.find( 'option' ).hide();
            if ( data !== undefined ) {
                $.each( donvidata[data], function ( index, value ) {
                    let option = phamvi.find( 'option[value="' + value.id + '"]' );

                    if ( option.length ) {
                        option.show();
                    }
                } );
            }
            phamvi.trigger( 'chosen:updated' );
        } );
        $( '#table-posts' ).init_dataTable( "users", {
            columns: [
                {
                    className: "client-avatar",
                    data: "avatar_url",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return "<img alt='Ảnh đại diện' src='" + data + "'>";
                    }
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
                    className: "client-email",
                    data: "email",
                    sortable: false
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Người dùng không kích hoạt";
                        let _icon = "<span class='label label-danger'>Inactive</span>";

                        data = data == 3 ? 1 : data;

                        switch ( data ) {
                            case 1:
                                _class = "active";
                                _title = "Người dùng đang kích hoạt";
                                _icon = "<span class='label label-warning'>Active</span>";
                                break;
                            case 2:
                                _class = "inactive";
                                _title = "Người dùng ẩn";
                                _icon = "<span class='label label-warning'>Hidden</span>";
                                break;
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                },
            ]
        } );
        $( document ).on( 'click', 'button.edit-field', function () {
            let group = $( this ).closest( '.input-group' );
            let input = group.find( 'input' );

            if ( input.attr( 'disabled' ) === undefined ) {
                input.attr( 'disabled', 'disabled' ).removeAttr( 'name' );
            } else {
                input.removeAttr( 'disabled' ).attr( 'name', 'password' );
            }
        } );
        /* Edit */
        $( document ).on( 'click', '#table-posts tbody > tr', function () {
            let row = $( this ),
                form = $( '#edit-post' ),
                user = OvicTable.row( this ).data(),
                chosen = [ 'donvi_id', 'role_ids', 'donvi_ids' ];

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );
                form.find( '.ovic-field-image img' ).attr( 'src', user.avatar_url );

                $.each( user, function ( index, value ) {
                    if ( form.find( '[name="' + index + '"]' ).length ) {
                        if ( index === 'status' && value == 3 ) {
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

                form.find( '.form-group .add-post' ).addClass( 'd-none' );
                form.find( '.field-password-confirmation' ).css( 'display', 'none' );
                form.find( '.field-password .input-group-append' ).css( 'display', 'flex' );
                form.find( '.form-group .edit-post,.form-group .delete-post' ).removeClass( 'd-none' );
            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }
        } );
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form = $( '#edit-post' ),
                table = $( '#table-posts' );

            table.find( 'tbody > tr' ).removeClass( 'active' );
            form.trigger( 'reset' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( '.ovic-field-image .ovic-image-remove' ).trigger( 'click' );
            form.find( '.field-password input' ).removeAttr( 'disabled' ).attr( 'name', 'password' );
            form.find( '.chosen-select' ).val( '' ).trigger( 'chosen:updated' );
            form.find( '.field-password-confirmation' ).css( 'display', 'flex' ).find( 'input' ).attr( 'name', 'password_confirmation' );
            form.find( '.field-password .input-group-append' ).css( 'display', 'none' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            return false;
        } );

        @if( user_can('add', $permission) )
        /* Add post */
        $( document ).on( 'click', '#edit-post .btn.add-post', function () {
            let button = $( this ),
                form = button.closest( 'form' ),
                data = form.serializeObject();

            button.add_new( "users", data );

            return false;
        } );
        @endif

        @if( user_can('edit', $permission) )
        /* Update post */
        $( document ).on( 'click', '#edit-post .btn.edit-post', function () {
            let button = $( this ),
                form = button.closest( 'form' ),
                data = form.serializeObject();

            button.update_post( "users", data, true );

            return false;
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status( "users",
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
                form = button.closest( 'form' ),
                data = form.serializeObject();

            button.remove_post( "users", data );

            return false;
        } );
        @endif
    </script>
@endpush

@section( 'content-table' )

    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-content">

                @include( name_blade('Backend.users.list') )

            </div>
        </div>
    </div>
    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">

                @include( name_blade('Backend.users.edit') )

            </div>
        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( name_blade('Backend.media.modal') )

@endpush


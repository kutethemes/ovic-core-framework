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

        UsersClassicController
        .field-password .input-group-append {
            display: none;
        }
    </style>
@endpush

@push( 'scripts.table.classic' )
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{-- Jquery Validate --}}
    <script src="{{ asset('js/plugins/validate/jquery.validate.min.js') }}"></script>
    {{-- script users --}}
    <script>
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
        $( '#table-posts' ).init_dataTable( "users-classic", {
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
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Oops, nothing found!",
            disable_search_threshold: 5
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
                modal = $( '#modal-edit-post' ),
                user = OvicTable.row( this ).data(),
                chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ];

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
                form.find( '.field-password .input-group-append' ).css( 'display', 'block' );
                form.find( '.form-group .edit-post,.form-group .delete-post' ).removeClass( 'd-none' );
            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }

            modal.modal( 'show' );
        } );
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form = $( '#edit-post' ),
                table = $( '#table-posts' ),
                modal = $( '#modal-edit-post' );

            form.find( '.ovic-field-image .ovic-image-remove' ).trigger( 'click' );
            form.find( '.field-password input' ).removeAttr( 'disabled' ).attr( 'name', 'password' );
            form.find( '.chosen-select' ).val( '' ).trigger( 'chosen:updated' );
            form.find( '.field-password-confirmation' ).css( 'display', 'block' ).find( 'input' ).attr( 'name', 'password_confirmation' );
            form.find( '.field-password .input-group-append' ).css( 'display', 'none' );

            table.find( 'tbody > tr' ).removeClass( 'active' );
            form.trigger( 'reset' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            modal.modal( 'show' );

            return false;
        } );
        @if( user_can('add', $permission) )
        /* Add post */
        $( document ).on( 'click', '#edit-post .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "users-classic", data );

            return false;
        } );
        @endif

        @if( user_can('edit', $permission) )
        /* Update post */
        $( document ).on( 'click', '#edit-post .btn.edit-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            data.dataTable = true;

            button.update_post( "users-classic", data, "#table-posts" );

            return false;
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "users-classic",
                "Tắt kích hoạt thành công",
                "Kích hoạt thành công"
            );

            return false;
        } );
        @endif

        @if( user_can('delete', $permission) )
        /* Remove post */
        $( document ).on( 'click', '#edit-post .btn.delete-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.remove_post( "users-classic", data );

            return false;
        } );
        @endif
    </script>
@endpush

@section( 'content-table-classic' )

    <div class="col-sm-12 full-height hide-sidebar">
        <div class="ibox full-height-scroll">

            @include( name_blade('Backend.users-classic.list') )

        </div>
    </div>

@endsection

@push( 'after-content' )

    {{-- https://getbootstrap.com/docs/4.1/components/modal/ --}}

    @include( name_blade('Backend.media.modal') )

    <div id="modal-edit-post" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @include( name_blade('Backend.users-classic.edit') )

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endpush

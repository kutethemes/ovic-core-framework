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

@extends( ovic_blade('Components.table') )

@section( 'title', 'QUẢN LÝ NGƯỜI DÙNG' )

@push( 'styles.table' )
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
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
    <!-- Chosen -->
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script>
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
                user = OvicTable.row( this ).data(),
                chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ];

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );
                form.find( '.ovic-field-image img' ).attr( 'src', user.avatar_url );

                $.each( user, function ( index, value ) {
                    if ( form.find( '[name="' + index + '"]' ).length ) {
                        if ( chosen.indexOf( index ) !== -1 ) {

                            value = JSON.parse( value );

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
                form.find( '.form-group .update-post,.form-group .remove-post' ).removeClass( 'd-none' );
            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "users",
                "Tắt kích hoạt thành công",
                "Kích hoạt thành công"
            );

            return false;
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
        /* Add post */
        $( document ).on( 'click', '.wrapper-content .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "users", data );

            return false;
        } );
        /* Update post */
        $( document ).on( 'click', '.wrapper-content .btn.update-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            data.dataTable = true;

            button.update_post( "users", data, "#table-posts" );

            return false;
        } );
        /* Remove post */
        $( document ).on( 'click', '.wrapper-content .btn.remove-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.remove_post( "users", data );

            return false;
        } );
    </script>
@endpush

@section( 'content-table' )

    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-content">

                @include( ovic_blade('Backend.users.list') )

            </div>
        </div>
    </div>
    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">

                @include( ovic_blade('Backend.users.edit') )

            </div>
        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( ovic_blade('Backend.media.modal') )

@endpush


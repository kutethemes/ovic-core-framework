@php
    /**
     * The main roles for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade( 'Components.table' ) )

@section( 'title', 'QUẢN LÝ NHÓM NGƯỜI DÙNG' )

@push( 'styles.table' )
    {{-- style roles --}}
    <style>
        .client-order {
            max-width: 45px;
            text-align: center;
        }

        .client-title,
        .client-desc {
            min-width: 200px;
        }
    </style>
@endpush

@push( 'scripts.table' )
    {{-- script roles --}}
    <script>
        $( '#table-posts' ).init_dataTable( "roles", {
            columns: [
                {
                    className: "client-order",
                    data: "ordering",
                    sortable: false
                },
                {
                    className: "client-name",
                    data: "name",
                    sortable: false
                },
                {
                    className: "client-title",
                    data: "title",
                    sortable: false
                },
                {
                    className: "client-desc",
                    data: "description",
                    sortable: false
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Nhóm không kích hoạt";
                        let _icon = "<span class='label label-danger'>Inactive</span>";

                        if ( data === 1 ) {
                            _class = "active";
                            _title = "Nhóm đang kích hoạt";
                            _icon = "<span class='label label-warning'>Active</span>";
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                }
            ]
        } );
        /* Edit */
        $( document ).on( 'click', '#table-posts tbody > tr', function () {
            let row = $( this ),
                form = $( '#edit-post' ),
                role = OvicTable.row( this ).data();

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );

                $.each( role, function ( index, value ) {
                    if ( form.find( '[name="' + index + '"]' ).length ) {
                        form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );
                    }
                } );

                form.find( '.form-group .add-post' ).addClass( 'd-none' );
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
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            return false;
        } );

        @if( user_can('add', $permission) )
        /* Add post */
        $( document ).on( 'click', '#edit-post .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "roles", data );

            return false;
        } );
        @endif

        @if( user_can('edit', $permission) )
        /* Update post */
        $( document ).on( 'click', '#edit-post .btn.edit-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.update_post( "roles", data, "#table-users" );

            return false;
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "roles",
                "Tắt nhóm thành công",
                "Kích hoạt nhóm thành công"
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

            button.remove_post( "roles", data );

            return false;
        } );
        @endif
    </script>
@endpush

@section( 'content-table' )

    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">

            @include( name_blade('Backend.roles.list') )

        </div>
    </div>
    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">

            @include( name_blade('Backend.roles.edit') )

        </div>
    </div>

@endsection

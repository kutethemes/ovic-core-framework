@php
    /**
     * The main permission for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade( 'Backend.app' ) )

@section( 'title', 'PHÂN QUYỀN CHỨC NĂNG' )

@push( 'styles' )
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- style permission --}}
    <style>
        .ibox {
            background-color: #fff;
        }

        .dd-handle .name {
            width: calc(100% - 128px);
            margin-right: 10px;
        }

        .dd-handle span.label {
            margin-right: 10px;
            width: 20px;
            height: 20px;
            text-align: center;
            padding: 0;
        }

        .dd-handle span.label i {
            line-height: 20px;
        }

        .dd-handle * {
            display: inline-block;
            vertical-align: middle;
        }

        .dd-handle {
            padding: 5px;
            pointer-events: none;
        }

        .dd-handle > .name,
        .dd-handle > .btn-group {
            pointer-events: all;
        }

        .dd-item > .dd-handle {
            padding-left: 25px;
        }

        .dd-item > button {
            position: absolute;
            left: 0;
            top: 3px;
        }

        .dd-handle > .btn-group > * {
            margin-bottom: 0;
        }

        .dd-handle > .btn-group > *:nth-child(2) {
            margin: 0 3px;
        }

        .dd-item > btn-group > * {
            margin-bottom: 0;
        }

        .dd-handle .btn i {
            line-height: 1.6;
        }

        li.dd-item > button[data-action="collapse"]::before {
            content: '\f0d8';
            font-family: Fontawesome;
        }

        li.dd-item > button[data-action="expand"]::before {
            content: '\f0d7';
            font-family: Fontawesome;
        }

        #nestable-menu > .btn:not(.add-new) {
            float: right;
        }

        div.client-detail {
            height: auto;
            padding-bottom: 40px;
        }

        .ibox-content.sk-loading::after {
            z-index: 2;
        }

        .ibox-content.sk-loading {
            height: calc(100% - 50px);
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

        /* The css-checkbox */
        .css-checkbox {
            display: block;
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            height: 25px;
        }

        /* Hide the browser's default checkbox */
        .css-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
            appearance: none;
            -moz-appearance: none; /* Firefox */
            -webkit-appearance: none; /* Safari and Chrome */
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #fff;
            box-shadow: 0 0 2px #aaa;
        }

        /* On mouse-over, add a grey background color */
        .css-checkbox:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .css-checkbox input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .css-checkbox input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .css-checkbox .checkmark:after {
            left: 9px;
            top: 6px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .elements-list li .nav-link {
            display: inline-block;
            width: 100%;
            padding: 15px;
        }
    </style>
@endpush

@push( 'scripts' )
    {{-- Sweet alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Nestable List --}}
    <script src="{{ asset('js/plugins/nestable/jquery.nestable.js') }}"></script>
    {{-- script permission --}}
    <script>
        var getData = function ( handle, data ) {
            handle.find( '.btn-group' ).each( function () {
                let vars = [],
                    id = $( this ).closest( '.dd-item' ).data( 'slug' );

                $( this ).find( 'input' ).each( function () {
                    if ( $( this ).is( ':checked' ) ) {
                        vars.push( 1 );
                    } else {
                        vars.push( 0 );
                    }
                } );

                data[id] = vars;
            } );

            return data;
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
        // activate Nestable for list menu
        $( '#menu-left' ).nestable( { maxDepth: 2 } );
        $( '#menu-top' ).nestable( { maxDepth: 2 } );
        // action Nestable for list menu
        $( '#nestable-menu' ).on( 'click', function ( e ) {
            var target = $( e.target ),
                action = target.data( 'action' );

            if ( action === 'expand-all' ) {
                $( '.dd' ).nestable( 'expandAll' );
            }
            if ( action === 'collapse-all' ) {
                $( '.dd' ).nestable( 'collapseAll' );
            }

            return false;
        } );
        /* chọn nhiều quyền */
        $( document ).on( 'click', '.dd-item > .dd-handle .name', function () {
            let checked = false,
                input = $( this ).closest( '.dd-handle' ).find( 'input' );

            input.each( function () {
                if ( $( this ).is( ':checked' ) ) {
                    checked = true;
                }
            } );

            if ( checked == true ) {
                input.each( function ( key, value ) {
                    $( this ).prop( 'checked', '' );
                } );
            } else {
                input.each( function ( key, value ) {
                    $( this ).prop( 'checked', 'checked' );
                } );
            }
        } );

        $( document ).on( 'change', '.dd-item > .dd-handle input', function ( e ) {
            var self = $( this ),
                items = self.closest( '.dd-item' );

            var item_id = items.find( '.dd-list input[name="' + self.attr( 'name' ) + '"]' );

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
        } );

        $( document ).on( 'click', '.dd-item.has-children > .dd-list input', function () {
            let input = $( this ),
                name = input.attr( 'name' ),
                list = input.closest( '.dd-list' ),
                item = list.prev( '.dd-handle' ).find( 'input[name="' + name + '"]' );

            list.find( 'input[name="' + name + '"]' ).each( function () {
                if ( $( this ).is( ':checked' ) && !item.is( ':checked' ) ) {
                    item.prop( 'checked', true );

                    return false;
                }
            } );
        } );
        /* chọn nhóm người dùng */
        $( document ).on( 'click', '.client-detail a.nav-link', function () {
            let button = $( this ),
                form = $( '#list-posts' ),
                id = button.data( 'id' ),
                ucase = button.data( 'ucase' );

            form.trigger( 'reset' );
            form.find( '[name="id"]' ).val( id ).trigger( 'change' );
            button.addClass( 'active' );
            button.closest( '.client-detail' ).find( 'a' ).not( button ).removeClass( 'active' );

            $.each( ucase, function ( index, value ) {
                let item = $( '#menu-' + index ),
                    add = item.find( 'input[name="add"]' ),
                    edit = item.find( 'input[name="edit"]' ),
                    del = item.find( 'input[name="delete"]' );

                if ( value[0] !== undefined && value[0] == 1 ) {
                    add.prop( 'checked', true );
                }
                if ( value[1] !== undefined && value[1] == 1 ) {
                    edit.prop( 'checked', true );
                }
                if ( value[2] !== undefined && value[2] == 1 ) {
                    del.prop( 'checked', true );
                }
            } );

            return false;
        } );
        /* Phân quyền */
        $( document ).on( 'click', '.ibox-title a.save-change', function () {
            let input = $( this ),
                form = $( '#list-posts' ),
                roleID = form.find( 'input[name="id"]' ).val(),
                role = $( '#role-' + roleID ),
                loading = form.closest( '.ibox-content' ),
                data = {};

            if ( roleID == 0 ) {
                form.trigger( 'reset' );
                swal( {
                    type: 'warning',
                    title: '',
                    text: 'Chọn nhóm người dùng trước khi phân quyền',
                    showConfirmButton: true
                } );
                return false;
            }

            if ( form.find( '.dd-handle' ).length ) {
                data = getData( form.find( '.dd-handle' ), data );
            }

            data.id = roleID;

            loading.addClass( 'sk-loading' );

            $.ajax( {
                url: "permission/" + roleID,
                type: 'PUT',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {
                    if ( response.status === 200 ) {
                        role.data( 'ucase', response.data );
                        role.find( '.label' ).html( response.count );

                        toastr.options = {
                            "preventDuplicates": true,
                        };

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
                    loading.removeClass( 'sk-loading' );
                    $( 'a#role-' + roleID ).trigger( 'click' );
                },
                error: function () {
                    loading.removeClass( 'sk-loading' );
                }
            } );
        } );
    </script>
@endpush

@section( 'content' )

    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">

            @include( name_blade('Backend.permission.edit') )

        </div>
    </div>
    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">

            @include( name_blade('Backend.permission.list') )

        </div>
    </div>

@endsection

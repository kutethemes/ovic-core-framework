@php
    /**
     * The main ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade( 'Backend.app' ) )

@section( 'title', 'QUẢN LÝ CHỨC NĂNG' )

@push( 'styles' )
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- style ucases --}}
    <style>
        .ibox {
            background-color: #fff;
        }

        #edit-post .btn-danger {
            float: left;
        }

        .head-group .button-group {
            float: right;
        }

        .client-status .inactive .label-warning {
            background-color: #ccc;
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

        .form-group.submit {
            margin-bottom: 0;
            margin-top: 1rem;
            text-align: right;
        }

        .dd-handle .name {
            width: calc(100% - 108px);
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
        }

        .dd-item > .dd-handle {
            padding-left: 25px;
        }

        .dd-item > button {
            position: absolute;
            left: 0;
            top: 3px;
        }

        .dd-handle .btn {
            padding: 0 6px;
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

        .dd-item.active > .dd-handle {
            background-color: #2c937e !important;
            color: #fff;
            border-color: #2c937e !important;
        }

        .dd-item.active > button {
            color: #fff;
        }

        .dd-item.active > .dd-handle .btn-primary.btn-outline,
        .dd-item.active > .dd-handle .btn-primary.btn-outline:hover,
        .dd-item.active > .dd-handle .btn-primary.btn-outline:focus {
            border-color: #fff;
            background-color: #fff;
            color: #1ab394;
        }

        .dd-item > .dd-handle.hidden {
            background-color: #ccc;
            border-color: #ccc;
        }

        .dd-item > .dd-handle.hidden .btn-primary.btn-outline,
        .dd-item > .dd-handle.hidden .btn-primary.btn-outline:hover,
        .dd-item > .dd-handle.hidden .btn-primary.btn-outline:focus {
            border-color: #fff;
            background-color: #fff;
            color: #1ab394;
        }

        .dd-item > .dd-handle.disable {
            background-color: #ed5565;
            border-color: #ed5565;
            color: #fff;
        }

        .dd-item > .dd-handle.disable .btn-primary.btn-outline,
        .dd-item > .dd-handle.disable .btn-primary.btn-outline:hover,
        .dd-item > .dd-handle.disable .btn-primary.btn-outline:focus {
            border-color: #fff;
            background-color: #fff;
            color: #1ab394;
        }

        div.client-detail {
            height: auto;
            padding-bottom: 40px;
        }

        .form-group .btn-group {
            width: 100%;
        }

        .form-group .btn-group .btn-white.active {
            color: #fff;
            background-color: #1ab394;
            border-color: #1ab394;
        }

        .form-group.field-access .btn-group .btn-white.active {
            color: #fff;
            background-color: #f8ac59;
            border-color: #f8ac59;
        }

        .field-module.hidden,
        .field-controller.hidden,
        .field-custom_link.hidden {
            display: none !important;
        }

        .ibox-content.sk-loading::after {
            z-index: 2;
        }

        .ibox-content.sk-loading {
            height: calc(100% - 50px);
        }

        .ibox-title h5 {
            margin: 0 0 12px;
        }

        .ibox-title .add-new,
        .ibox-title .reload-page {
            margin-right: 10px;
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

@endpush

@push( 'scripts' )
    {{-- Sweet alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Nestable List --}}
    <script src="{{ asset('js/plugins/nestable/jquery.nestable.js') }}"></script>
    {{-- script ucases --}}
    <script>
        toastr.options = {
            "preventDuplicates": true,
        };
        var updateMenu = function ( e, item, source, destination, position ) {
                var list = item.closest( '.dd' ),
                    data = list.nestable( 'serialize' ),
                    loading = list.closest( '.ibox-content' );

                loading.addClass( 'sk-loading' );

                $.ajax( {
                    url: "ucases/create",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        data: data,
                        position: list.attr( 'id' ) === 'menu-left' ? 'left' : 'top'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    success: function ( response ) {
                        toastr[response.status]( response.message );
                        loading.removeClass( 'sk-loading' );
                    }
                } );
            },
            template = function ( data, addNew = false ) {
                let html = '',
                    item = '',
                    status = '';

                if ( data.status == 0 ) {
                    status = 'disable';
                }
                if ( data.status == 2 ) {
                    status = 'hidden';
                }

                item += '<div id="menu-' + data.id + '" class="dd-handle ' + status + '" data-slug="' + data.slug + '">';
                item += '   <span class="label label-info"><i class="' + data.icon + '"></i></span>';
                item += '   <div class="name">' + data.title + '</div>';
                item += '   <div class="dd-nodrag btn-group">';
                item += '       <button class="btn btn-outline btn-primary edit">Edit</button>';
                item += '       <button class="btn btn-danger remove"><i class="fa fa-trash-o"></i></button>';
                item += '   </div>';
                item += '</div>';

                if ( addNew ) {
                    html += '<li class="dd-item" data-id="' + data.id + '">';
                    html += item;
                    html += '</li>';
                } else {
                    html = item;
                }

                return html;
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
        $.fn.removeMenu = function ( data ) {
            swal( {
                title: "Bạn có chắc muốn xóa \n\"" + data.title + "\"?",
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
                        url: "ucases/" + data.id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        success: function ( response ) {

                            let menu = $( '#menu-' + data.id ).closest( '.dd-item' ),
                                mainmenu = menu.closest( '.dd' );

                            if ( response.status === 'success' ) {
                                menu.remove();
                            }

                            if ( !mainmenu.find( '.dd-item' ).length ) {
                                mainmenu.html( '<div class="dd-empty"></div>' );
                            }

                            swal( {
                                type: response.status,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                            } );

                            $( '.ibox-title .add-new' ).trigger( 'click' );
                        },
                    } );

                }
            } );
        };
        // activate Nestable for list menu
        $( '#menu-left' ).nestable( { maxDepth: 2 } ).nestable( 'collapseAll' ).on( 'dragEnd', updateMenu );
        $( '#menu-top' ).nestable( { maxDepth: 2 } ).nestable( 'collapseAll' ).on( 'dragEnd', updateMenu );

        // action Nestable for list menu
        $( document ).on( 'click', '#nestable-menu', function ( e ) {
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
        /* add new */
        $( document ).on( 'click', '.ibox-title .add-new', function () {
            let form = $( '#edit-post' );

            form.trigger( 'reset' );
            form.find( '.ovic-icon-remove' ).trigger( 'click' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( 'input[name="access"]' ).val( 1 ).trigger( 'change' );
            form.find( 'input[name="position"]' ).val( 'left' ).trigger( 'change' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );
            form.find( '.field-position' ).removeClass( 'd-none' );
            $( '.dd .dd-item' ).removeClass( 'active' );
            $( '.ibox-title a.hide' ).trigger( 'click' );

            return false;
        } );
        /* select button */
        $( document ).on( 'click', '.form-group .btn-group', function ( e ) {
            var target = $( e.target ),
                form = target.closest( 'form' ),
                name = target.data( 'name' ),
                value = target.val();

            target.addClass( 'active' ).siblings().removeClass( 'active' );
            form.find( 'input[name="' + name + '"]' ).val( value ).trigger( 'change' );
        } );
        /* controller */
        $( document ).on( 'click', '.ibox-title .dropdown-form a', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                custom_link = form.find( '.field-custom_link' ),
                controller = form.find( '.field-controller' ),
                module = form.find( '.field-module' );

            if ( button.hasClass( 'hide' ) ) {

                if ( !custom_link.hasClass( 'hidden' ) ) custom_link.addClass( 'hidden' );
                if ( !module.hasClass( 'hidden' ) ) module.addClass( 'hidden' );
                if ( !controller.hasClass( 'hidden' ) ) controller.addClass( 'hidden' );

                form.find( '[name="route[custom_link]"]' ).val( '' ).trigger( 'change' );
                form.find( '[name="route[module]"]' ).val( '' ).trigger( 'change' );
                form.find( '[name="route[controller]"]' ).val( '' ).trigger( 'change' );
            }
            if ( button.hasClass( 'controller' ) ) {

                if ( !custom_link.hasClass( 'hidden' ) ) custom_link.addClass( 'hidden' );
                if ( module.hasClass( 'hidden' ) ) module.removeClass( 'hidden' );
                if ( controller.hasClass( 'hidden' ) ) controller.removeClass( 'hidden' );

                form.find( '[name="route[custom_link]"]' ).val( '' ).trigger( 'change' );
            }
            if ( button.hasClass( 'custom_link' ) ) {

                if ( custom_link.hasClass( 'hidden' ) ) custom_link.removeClass( 'hidden' );
                if ( !module.hasClass( 'hidden' ) ) module.addClass( 'hidden' );
                if ( !controller.hasClass( 'hidden' ) ) controller.addClass( 'hidden' );

                form.find( '[name="route[module]"]' ).val( '' ).trigger( 'change' );
                form.find( '[name="route[controller]"]' ).val( '' ).trigger( 'change' );
            }

            return false;
        } );
        $( document ).on( 'change', '#edit-post input', function ( e ) {
            var target = $( e.target ),
                form = target.closest( 'form' ),
                name = target.attr( 'name' ),
                value = target.val();

            if ( name === 'access' || name === 'position' ) {
                form.find( '.form-group [value="' + value + '"]' ).addClass( 'active' ).siblings().removeClass( 'active' );
            }
        } );
        /* Edit */
        $( document ).on( 'click', '.dd-handle .btn.edit', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                loading = form.closest( '.ibox-content' ),
                menu = $( '.dd' ),
                icon = form.find( '.ovic-field-icon' ),
                item = button.closest( '.dd-item' );

            if ( !item.hasClass( 'active' ) ) {

                loading.addClass( 'sk-loading' );

                $.ajax( {
                    url: "ucases/" + item.data( 'id' ) + "/edit",
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                    },
                    success: function ( response ) {

                        if ( response.status === 'success' ) {
                            $.each( response.data, function ( index, value ) {
                                if ( $.isPlainObject( value ) ) {
                                    $.each( value, function ( objIndex, objValue ) {
                                        let name = '[name="' + index + '[' + objIndex + ']"]';

                                        if ( form.find( name ).length ) {
                                            if ( objIndex === 'icon' ) {
                                                icon.find( 'i' ).removeAttr( 'class' ).addClass( objValue );
                                                icon.find( 'input' ).val( objValue ).trigger( 'change' );
                                                icon.find( '.ovic-icon-preview' ).removeClass( 'd-none' );
                                                icon.find( '.ovic-icon-remove' ).removeClass( 'd-none' );
                                            } else {
                                                form.find( name ).val( objValue ).trigger( 'change' );
                                            }
                                        }
                                    } );
                                } else if ( form.find( '[name="' + index + '"]' ).length ) {
                                    form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );
                                }
                            } );
                            /* active */
                            item.addClass( 'active' );
                            menu.find( '.dd-item' ).not( item ).removeClass( 'active' );
                            form.find( '.form-group .add-post' ).addClass( 'd-none' );
                            form.find( '.field-position' ).addClass( 'd-none' );
                            form.find( '.form-group .edit-post,.form-group .delete-post' ).removeClass( 'd-none' );
                            loading.prev( '.ibox-title' ).find( 'a.controller' ).trigger( 'click' );
                        } else {
                            swal( {
                                type: response.status,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                            } );
                        }

                        loading.removeClass( 'sk-loading' );
                    },
                } );

            } else {
                $( '.ibox-title .add-new' ).trigger( 'click' );
            }
        } );

        @if( user_can('add', $permission) )
        /* Add post */
        $( document ).on( 'click', '#edit-post .btn.add-post', function () {
            let button = $( this ),
                form = button.closest( '#edit-post' ),
                loading = form.closest( '.ibox-content' ),
                data = form.serializeObject(),
                menuLeft = $( '#menu-left' ),
                menuTop = $( '#menu-top' );

            loading.addClass( 'sk-loading' );

            $.ajax( {
                url: 'ucases',
                type: 'POST',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {

                    if ( response.status === 200 ) {

                        data.id = response.id;
                        data.icon = form.find( 'input[name="route[icon]"]' ).val();
                        let html = template( data, true );

                        if ( data.position === 'left' ) {
                            menuLeft.append( html );
                            menuLeft.find( '.dd-empty' ).remove();
                        } else {
                            menuTop.append( html );
                            menuTop.find( '.dd-empty' ).remove();
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

                    loading.removeClass( 'sk-loading' );
                },
            } );
        } );
        @endif

        @if( user_can('edit', $permission) )
        /* Update post */
        $( document ).on( 'click', '#edit-post .btn.edit-post', function () {
            let button = $( this ),
                form = button.closest( '#edit-post' ),
                loading = $( '.ibox-content.ibox-list' ),
                data = form.serializeObject();

            loading.addClass( 'sk-loading' );

            $.ajax( {
                url: "ucases/" + data.id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {
                    if ( response.status === 200 ) {

                        data.icon = form.find( 'input[name="route[icon]"]' ).val();
                        let html = template( data );

                        $( '#menu-' + data.id ).replaceWith( html );

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
                },
            } );

            return false;
        } );
        @endif

        @if( user_can('delete', $permission) )
        /* Remove post */
        $( document ).on( 'click', '#edit-post .btn.delete-post', function () {
            let button = $( this ),
                form = button.closest( '#edit-post' ),
                data = form.serializeObject();

            button.removeMenu( data );

            return false;
        } );
        $( document ).on( 'click', '.dd-handle .btn.remove', function () {
            let data = [],
                button = $( this ),
                handle = button.closest( '.dd-handle' ),
                title = handle.find( '.name' ).html();

            data.id = handle.closest( '.dd-item' ).data( 'id' );
            data.title = title;

            button.removeMenu( data );

            return false;
        } );
        @endif
    </script>

@endpush

@section( 'content' )

    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">

            @include( name_blade('Backend.ucases.edit') )

        </div>
    </div>
    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">

            @include( name_blade('Backend.ucases.list') )

        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( name_blade('Fields.icon.modal') )

@endpush

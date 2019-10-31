@php
    /**
     * The icon modal for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles' )
    <style>
        .ovic-field-icon .ovic-icon-preview i {
            display: inline-block;
            font-size: 15px;
            width: 30px;
            height: 33px;
            line-height: 33px;
            margin-right: 5px;
            text-align: center;
            vertical-align: top;
            color: #555;
            border: 1px solid #ccc;
            background-color: #f7f7f7;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            -moz-box-sizing: content-box;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
        }

        .ovic-field-icon .button {
            margin-right: 5px;
        }

        .ovic-field-icon .button {
            margin-right: 5px;
        }

        .ovic-field-icon input {
            display: none;
        }

        .ovic-modal {
            display: none;
            position: fixed;
            z-index: 100101;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .ovic-modal-table {
            display: table;
            width: 100%;
            height: 100%;
        }

        .ovic-modal-table-cell {
            display: table-cell;
            vertical-align: middle;
            margin: 100px 0;
        }

        .ovic-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
            opacity: 0.5;
        }

        .ovic-modal-inner {
            position: relative;
            z-index: 10;
            width: 760px;
            height: 750px;
            margin: 0 auto;
            background-color: #fff;
        }

        .ovic-modal-title {
            position: relative;
            background-color: #fcfcfc;
            border-bottom: 1px solid #ddd;
            height: 36px;
            font-size: 16px;
            font-weight: 600;
            line-height: 36px;
            margin: 0;
            padding: 0 36px 0 16px;
        }

        .ovic-modal-close {
            color: #666;
            padding: 0;
            position: absolute;
            top: 0;
            right: 0;
            width: 36px;
            height: 36px;
            text-align: center;
            background: none;
            border: none;
            cursor: pointer;
        }

        .ovic-modal-close::before {
            font: normal 20px/36px dashicons;
            content: "\f158";
            vertical-align: top;
            width: 36px;
            height: 36px;
        }

        .ovic-text-center {
            text-align: center;
        }

        .ovic-modal-header {
            width: 100%;
            padding: 16px 0;
            background-color: #f5f5f5;
            border-bottom: 1px solid #eee;
        }

        .ovic-modal-header select {
            display: block;
            width: 250px;
            margin: 0 auto;
            background-color: #fff;
        }

        .ovic-modal-icon .ovic-icon-title {
            padding: 15px 0;
            margin: 4px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #eee;
            background-color: #f7f7f7;
        }

        .ovic-modal-icon .ovic-icon-search {
            width: 250px;
            height: 40px;
            line-height: 40px;
        }

        .ovic-modal-icon .ovic-modal-content {
            padding: 10px;
            height: 618px;
        }

        .ovic-modal-content {
            position: relative;
            overflow: hidden;
            overflow-y: auto;
            height: 592px;
        }

        .ovic-modal-loading {
            display: none;
            position: absolute;
            left: calc(50% - 10px);
            top: calc(50% - 10px);
        }

        .ovic-loading {
            position: relative;
            width: 20px;
            height: 20px;
            background: #ccc;
            -moz-border-radius: 20px;
            -webkit-border-radius: 20px;
            border-radius: 20px;
            -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.07);
            -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.07);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.07);
        }

        .ovic-modal-icon a {
            display: inline-block;
            padding: 4px;
            cursor: pointer;
        }

        .ovic-modal-icon a .ovic-icon {
            position: relative;
            padding: 4px;
            display: inline-block;
            font-size: 14px;
            width: 30px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            vertical-align: top;
            color: #555;
            border: 1px solid #ccc;
            background-color: #f7f7f7;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
            -moz-box-sizing: content-box;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
        }
    </style>
@endpush

@push( 'scripts' )
    <script>
        var iconFont = {};

        $( document ).ready( function () {

            $( '.ovic-field-icon' ).each( function () {

                var $this = $( this );

                $this.on( 'click', '.ovic-icon-add', function ( e ) {

                    e.preventDefault();

                    var $button = $( this ),
                        $modal = $( '#ovic-modal-icon' );

                    $modal.show();
                    iconFont.$icon_target = $this;

                    if ( !iconFont.icon_modal_loaded ) {

                        $modal.find( '.ovic-modal-loading' ).show();

                        $.get( 'get-icons', {
                            _token: $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        } ).done( function ( response ) {

                            $modal.find( '.ovic-modal-loading' ).hide();

                            iconFont.icon_modal_loaded = true;

                            var $load = $modal.find( '.ovic-modal-load' ).html( response.content );

                            $load.on( 'click', 'a', function ( e ) {

                                e.preventDefault();

                                var icon = $( this ).data( 'ovic-icon' );

                                iconFont.$icon_target.find( 'i' ).removeAttr( 'class' ).addClass( icon );
                                iconFont.$icon_target.find( 'input' ).val( icon ).trigger( 'change' );
                                iconFont.$icon_target.find( '.ovic-icon-preview' ).removeClass( 'd-none' );
                                iconFont.$icon_target.find( '.ovic-icon-remove' ).removeClass( 'd-none' );

                                $modal.hide();

                            } );

                            $modal.on( 'change keyup', '.ovic-icon-search', function () {

                                var value = $( this ).val(),
                                    $icons = $load.find( 'a' );

                                $icons.each( function () {

                                    var $elem = $( this );

                                    if ( $elem.data( 'ovic-icon' ).search( new RegExp( value, 'i' ) ) < 0 ) {
                                        $elem.hide();
                                    } else {
                                        $elem.show();
                                    }

                                } );

                            } );

                            $modal.on( 'click', '.ovic-modal-close, .ovic-modal-overlay', function () {
                                $modal.hide();
                            } );

                        } ).fail( function ( response ) {
                            $modal.find( '.ovic-modal-loading' ).hide();
                            $modal.find( '.ovic-modal-load' ).html( response.error );
                            $modal.on( 'click', function () {
                                $modal.hide();
                            } );
                        } );
                    }

                } );

                $this.on( 'click', '.ovic-icon-remove', function ( e ) {
                    e.preventDefault();
                    $this.find( '.ovic-icon-preview' ).addClass( 'd-none' );
                    $this.find( 'input' ).val( '' ).trigger( 'change' );
                    $( this ).addClass( 'd-none' );
                } );

            } );
        } );
    </script>
@endpush

<div id="ovic-modal-icon" class="ovic-modal ovic-modal-icon">
    <div class="ovic-modal-table">
        <div class="ovic-modal-table-cell">
            <div class="ovic-modal-overlay"></div>
            <div class="ovic-modal-inner">
                <div class="ovic-modal-title">
                    Add Icon
                    <div class="ovic-modal-close ovic-icon-close"></div>
                </div>
                <div class="ovic-modal-header ovic-text-center">
                    <input type="text"
                           placeholder="Search a Icon..."
                           class="ovic-icon-search"/>
                </div>
                <div class="ovic-modal-content">
                    <div class="ovic-modal-loading">
                        <div class="ovic-loading"></div>
                    </div>
                    <div class="ovic-modal-load"></div>
                </div>
            </div>
        </div>
    </div>
</div>

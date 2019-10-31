@php
    /**
     * The template modal for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles' )
    <style>
        div.modal-content {
            width: 100vw !important;
            height: calc(100vh - 50px) !important;
            border: none;
            border-radius: 0;
            box-shadow: none;
            display: block;
        }

        div.modal-footer {
            box-shadow: none;
            border-radius: 0;
            border-top: 1px solid #e7eaec;
        }

        div.inmodal .modal-header {
            padding: 10px;
            border-bottom: 1px solid #e7eaec;
        }

        .modal-footer {
            background-color: #fff;
        }

        .modal.show .modal-dialog {
            transform: none;
            max-width: inherit;
            margin: 0;
        }

        .modal-open .modal {
            padding: 0 !important;
        }

        .ovic-field-image .image-select {
            display: inline-block;
        }

        .ovic-field-image .image-preview {
            margin-bottom: 10px;
        }

        .ovic-field-image .group-button > * {
            margin: auto 5px;
        }

        .ovic-field-image img {
            border-width: 3px;
            max-width: 96px;
        }

        .ovic-field-image img:hover {
            border-color: #23c6c8;
        }
    </style>
@endpush

@push( 'scripts' )
    <script>

        var imageVar = {};

        $( document ).ready( function () {

            $( '.ovic-field-image' ).each( function () {

                var $this = $( this );

                $this.on( 'click', '.ovic-image-add', function ( e ) {

                    e.preventDefault();

                    var $button = $( this ),
                        $modal = $( '#modal-media' );

                    $modal.modal( 'show' );
                    imageVar.$image_target = $this;

                    if ( !imageVar.image_modal_loaded ) {

                        $modal.find( '.sk-spinner' ).show();

                        $.post( "{{ route('upload.modal') }}", {
                            _token: $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        } ).done( function ( response ) {

                            $modal.find( '.sk-spinner' ).hide();

                            imageVar.image_modal_loaded = true;

                            var $load = $modal.find( '.content-previews' ).html( response.content );

                            /* Tạo thư mục */
                            $( '#jstree1' ).jstree( {
                                "core": {
                                    "data": JSON.parse( response.directories )
                                },
                            } );

                            $modal.on( 'click', '.btn-primary.selected', function () {

                                let file_box = $load.find( '.file-box.active' );

                                if ( file_box.length && file_box.find( 'img' ).length ) {
                                    let id = file_box.data( 'id' );
                                    let src = file_box.find( 'img' ).attr( 'src' );

                                    imageVar.$image_target.find( 'input' ).val( id ).trigger( 'change' );
                                    imageVar.$image_target.find( 'img' ).attr( 'src', src );
                                }

                                $modal.modal( 'hide' );

                            } );

                        } ).fail( function ( response ) {
                            $modal.find( '.sk-spinner' ).hide();
                            $modal.find( '.content-previews' ).html( response );
                        } );
                    }

                } );

                $this.on( 'click', '.ovic-image-remove', function ( e ) {
                    e.preventDefault();
                    let preview = $this.find( '.image-preview' );
                    let placeholder = preview.data( 'placeholder' );

                    preview.find( 'img' ).attr( 'src', placeholder );
                    $this.find( 'input' ).val( '0' ).trigger( 'change' );
                } );

            } );
        } );
    </script>
@endpush

<div class="modal inmodal" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thư viện</h4>
            </div>
            <div class="modal-body row full-height-content">
                @include( ovic_blade('Backend.media.data'), ['multi' => false] )
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary selected" data-dismiss="modal">Chọn ảnh</button>
            </div>
        </div>
    </div>
</div>

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
    {{-- style modal media --}}
    <style>
        .ovic-field-image .image-select {
            display: inline-block;
        }

        .ovic-field-image .image-preview {
            margin-bottom: 10px;
            display: inline-block;
        }

        .ovic-field-image .group-button > * {
            margin: auto 5px;
        }

        .ovic-field-image img {
            border-width: 3px;
            height: 96px;
            width: 96px;
        }

        .ovic-field-image img:hover {
            border-color: #23c6c8;
        }
    </style>
@endpush

@if( user_can( 'add', 'upload' ) )

    @push( 'scripts' )
        {{-- script modal media --}}
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

                            $.get( "upload/create", {
                                _token: $( 'meta[name="csrf-token"]' ).attr( 'content' )
                            } ).done( function ( response ) {

                                $modal.find( '.sk-spinner' ).hide();

                                imageVar.image_modal_loaded = true;

                                var $load = $modal.find( '.content-previews' ).html( response.content );

                                /* Tạo thư mục */
                                treeFolder( response.directories );

                                $modal.on( 'click', '.btn-primary.save-modal', function () {

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

    @include( name_blade('Components.modal'), [
       'title'         => !empty($title)        ? $title        : 'Thư viện',
       'id'            => !empty($id)           ? $id           : 'modal-media',
       'text_close'    => !empty($text_close)   ? $text_close   : 'Đóng',
       'text_save'     => !empty($text_save)    ? $text_save    : 'Chọn ảnh',
       'content'       => name_blade('Backend.media.data'),
       'attribute'     => [
            'multi_file'    => !empty($multi_file) ? $multi_file : false,
            'multi'         => false
       ],
    ])

@endif

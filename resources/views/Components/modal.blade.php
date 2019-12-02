@php
    /**
     * The modal template for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@php
    $modal_footer     = true;
    $attribute        = !empty( $attribute ) ? $attribute : [];
    if ( empty( $text_close ) && empty( $text_save ) ){
        $modal_footer = false;
    }
@endphp

@if (!empty($modal_size))
    @push( 'styles' )
        <style>
            @media (min-width: {{ $modal_size }}px) {
                {{ "#{$id}" }} .modal-content {
                    width: {{ $modal_size }}px;
                }
            }
        </style>
    @endpush
@endif

<div id="{{ $id }}"
     class="modal inmodal fade{{ !$modal_footer ? ' no-footer' : '' }}{{ empty( $title ) ? ' no-header' : '' }}"
     data-in="{{ !empty( $animated_in ) ? $animated_in : 'slideInLeft' }}"
     data-out="{{ !empty( $animated_out ) ? $animated_out : 'slideOutLeft' }}"
     tabindex="-1"
     role="dialog">
    <div class="modal-dialog animated" role="document">
        <div class="modal-content">
            @if ( !empty( $title ) )
                <div class="modal-header">
                    <h5 class="modal-title">{{ !empty( $title ) ? $title : 'Modal title' }}</h5>
                    <button class="btn btn-danger close-modal" type="button" data-dismiss="modal" aria-label="Close">
                        &times;
                    </button>
                </div>
            @endif

            <div class="modal-body full-height-content">

                <div class="row full-height">

                    @include( $content, $attribute )

                </div>

            </div>

            @if( $modal_footer )
                <div class="modal-footer">
                    @if( !empty( $text_close ) )
                        <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">
                            {!! $text_close !!}
                        </button>
                    @endif
                    @if( !empty( $text_save ) )
                        <button type="button" class="btn btn-primary save-modal">
                            {!! $text_save !!}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

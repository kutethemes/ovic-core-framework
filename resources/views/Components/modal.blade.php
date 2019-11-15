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
    $footer = true;
    if ( empty( $text_close ) && empty( $text_save ) ){
        $footer = false;
    }
    $attribute  = !empty( $attribute ) ? $attribute : [];
@endphp

<div id="{{ $id }}" class="modal inmodal fade animated {{ !$footer ? ' no-footer' : '' }}"
     data-in="{{ !empty( $animated_in ) ? $animated_in : 'slideInLeft' }}"
     data-out="{{ !empty( $animated_out ) ? $animated_out : 'slideOutLeft' }}"
     tabindex="-1"
     role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ !empty( $title ) ? $title : 'Modal title' }}</h5>
                <button class="btn btn-danger close-modal" type="button" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>

            <div class="modal-body full-height-content">

                <div class="row full-height">

                    @include( $content, $attribute )

                </div>

            </div>

            @if( $footer )
                <div class="modal-footer">
                    @if( !empty( $text_close ) )
                        <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">
                            {{ $text_close }}
                        </button>
                    @endif
                    @if( !empty( $text_save ) )
                        <button type="button" class="btn btn-primary save-modal">
                            {{ $text_save }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@php
    /**
     * The template field select image for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@php
    $placeholder    = ( !empty( $placeholder ) ) ? $placeholder : 'img/a_none.jpg';
    $alt            = ( !empty( $alt ) ) ? $alt : 'Placeholder';
    $image          = ( !empty( $value ) && !empty( $image ) ) ? $image : $placeholder;
@endphp

<div class="ovic-field-image">
    <div class="image-select">
        <div class="image-preview" data-placeholder="{{ $placeholder }}">
            <img alt="{{ $alt }}" class="rounded-circle img-thumbnail" src="{{ $image }}">
        </div>
        @if( user_can( 'add', 'upload' ) )
            <div class="group-button">
                <a href="#" class="btn btn-primary ovic-image-add">
                    Add Image
                </a>
                <a href="#" class="btn btn-danger ovic-image-remove">
                    Remove Image
                </a>
            </div>
            <input type="hidden" name="{{ $name }}" value="{{ $value }}"/>
        @endif
    </div>
</div>

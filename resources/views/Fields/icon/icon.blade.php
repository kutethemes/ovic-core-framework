@php
    /**
     * The icon field for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@php
    $hidden = ( empty( $value ) ) ? ' d-none' : '';
@endphp

<div class="ovic-field-icon">
    <div class="ovic-icon-select">
        <span class="ovic-icon-preview{{ $hidden }}">
            <i class="{{ $value }}"></i>
        </span>
        <a href="#" class="btn btn-primary ovic-icon-add">
            Add Icon
        </a>
        <a href="#" class="btn btn-warning ovic-icon-remove{{ $hidden }}">
            Remove Icon
        </a>
        <input type="text" name="{{ $name }}" value="{{ $value }}" class="ovic-icon-value"/>
    </div>
</div>

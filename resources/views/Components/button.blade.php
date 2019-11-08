@php
    /**
     * The button for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<button type="{{ $type }}" class="btn {{ $class }}">
    @if( !empty( $icon ) )
        <i class="{{ $icon  }}"></i>
    @endif
    {{ $text }}
</button>

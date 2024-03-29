@php
    /**
     * The menu item ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@php
    switch ( $data['status'] ){
        case 0 :
            $status = ' disable';
        break;

        case 2 :
            $status = ' hidden';
        break;

        default:
            $status = '';
        break;
    }
@endphp

<div id="menu-{{ $data['id'] }}" class="dd-handle{{ $status }}" data-slug="{{ $data['slug'] }}">
    <span class="label label-info">
        <i class="{{ isset( $data['route']['icon'] ) ? $data['route']['icon'] : '' }}"></i>
    </span>
    <div class="name">{{ $data['title'] }}</div>
    <div class="dd-nodrag btn-group">
        @if( user_can('edit', $permission) )
            <button class="btn btn-outline btn-primary edit">
                Edit
            </button>
        @endif
        @if( user_can('delete', $permission) )
            <button class="btn btn-danger remove">
                <i class="fa fa-trash-o"></i>
            </button>
        @endif
    </div>
</div>

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

<div id="menu-{{ $data['id'] }}" class="dd-handle{{ $status }}">
        <span class="label label-info">
            <i class="{{ $data['route']['icon'] }}"></i>
        </span>
    <div class="name">
        {{ $data['title'] }}
    </div>
    <div class="dd-nodrag btn-group">
        <button class="btn btn-outline btn-primary edit">
            Edit
        </button>
        <button class="btn btn-danger remove">
            <i class="fa fa-trash-o"></i>
        </button>
    </div>
</div>

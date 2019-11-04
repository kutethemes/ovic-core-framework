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
    $router = json_decode( $data['router'], true );
@endphp

<div id="menu-{{ $data['id'] }}" class="dd-handle">
    <span class="label label-info">
        <i class="{{ $router['icon'] }}"></i>
    </span>
    <div class="name">
        {{ $data['title'] }}
    </div>
    <div class="btn-group">
        <label class="css-checkbox" for="add-{{ $data['id'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Thêm">
            <input id="add-{{ $data['id'] }}" type="checkbox" name="add"
                   value="0">
            <span class="checkmark"></span>
        </label>
        <label class="css-checkbox" for="edit-{{ $data['id'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Sửa">
            <input id="edit-{{ $data['id'] }}" type="checkbox" name="edit"
                   value="0">
            <span class="checkmark"></span>
        </label>
        <label class="css-checkbox" for="delete-{{ $data['id'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Xóa">
            <input id="delete-{{ $data['id'] }}" type="checkbox" name="delete"
                   value="0">
            <span class="checkmark"></span>
        </label>
    </div>
</div>

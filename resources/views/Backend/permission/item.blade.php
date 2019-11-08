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

<div id="menu-{{ $data['slug'] }}" class="dd-handle">
    <span class="label label-info">
        <i class="{{ $data['route']['icon'] }}"></i>
    </span>
    <div class="name">
        {{ $data['title'] }}
    </div>
    <div class="btn-group">
        <label class="css-checkbox" for="add-{{ $data['slug'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Thêm">
            <input id="add-{{ $data['slug'] }}" type="checkbox" name="add"
                   value="0">
            <span class="checkmark"></span>
        </label>
        <label class="css-checkbox" for="edit-{{ $data['slug'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Sửa">
            <input id="edit-{{ $data['slug'] }}" type="checkbox" name="edit"
                   value="0">
            <span class="checkmark"></span>
        </label>
        <label class="css-checkbox" for="delete-{{ $data['slug'] }}"
               data-trigger="hover" data-toggle="popover"
               data-placement="top" data-content="Xóa">
            <input id="delete-{{ $data['slug'] }}" type="checkbox" name="delete"
                   value="0">
            <span class="checkmark"></span>
        </label>
    </div>
</div>

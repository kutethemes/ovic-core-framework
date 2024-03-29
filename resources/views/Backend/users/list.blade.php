@php
    /**
     * The table users for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="head-group col-sm-12">
    @if( !user_can('add', $permission) )
        <button class="btn btn-primary add-new" type="button">
            <i class="fa fa-plus"></i>
            Add new
        </button>
    @endif
    <div class="button-group">
        <span class="font-bold">Lọc theo:</span>
        <div class="btn-group sorting">
            <button class="btn btn-white" type="button" value="1">
                Kích hoạt
            </button>
            <button class="btn btn-white" type="button" value="2">
                Kích hoạt ẩn
            </button>
            <button class="btn btn-white" type="button" value="0">
                Không kích hoạt
            </button>
        </div>
    </div>
</div>
<div class="clients-list">
    <table id="table-posts" class="table table-striped table-hover" style="width:100%">
        <thead>
        <tr>
            <th class="client-avatar"></th>
            <th class="client-email">Tài khoản/Email</th>
            <th class="client-name">Chủ tài khoản</th>
            <th class="client-donvi">Đơn vị</th>
            <th class="client-status">Trạng thái</th>
        </tr>
        </thead>
    </table>
</div>

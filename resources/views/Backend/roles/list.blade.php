@php
    /**
     * The table roles for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="ibox-content">
    <div class="head-group col-sm-12">
        <button class="btn btn-primary add-new" type="submit">
            <i class="fa fa-plus"></i>
            Add new
        </button>
        <div class="button-group">
            <span class="font-bold">Lọc theo:</span>
            <div class="btn-group sorting">
                <button class="btn btn-white" type="button" value="1">
                    Kích hoạt
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
                <th class="client-order">Order</th>
                <th class="client-name">Tên riêng</th>
                <th class="client-title">Tên hiển thị</th>
                <th class="client-desc">Mô tả</th>
                <th class="client-status">Trạng thái</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

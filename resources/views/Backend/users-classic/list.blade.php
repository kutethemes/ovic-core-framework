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

<div class="head-group">
    <form class="table-filter">
        <div class="form-group filter-select">
            <div class="input-control">
                <select class="chosen-select" name="donvi_id" data-placeholder="== Lọc theo đơn vị ==">
                    <option value=""></option>
                    @if( !empty( $donvis) )
                        {!!
                            _menu_tree( $donvis, [
                               'title'  =>  'tendonvi',
                               'type'   =>  'dropdown',
                               'groupBy'=>  1,
                            ]);
                        !!}
                    @endif
                </select>
            </div>
            <button class="btn btn-white" type="button" value="0">
                Lọc
            </button>
        </div>
    </form>
</div>
<div class="ibox-content">
    <div class="clients-list">
        <table id="table-posts" class="table table-striped table-hover" style="width:100%">
            <thead>
            <tr>
                <th class="client-id">
                    <input id="select-all" type="checkbox"
                           data-trigger="hover" data-toggle="popover"
                           data-placement="top" data-content="Chọn tất cả">
                </th>
                <th class="client-avatar"></th>
                <th class="client-name">Tên hiển thị</th>
                <th class="client-donvi">Đơn vị</th>
                <th class="client-email">Email</th>
                <th class="client-status">Trạng thái</th>
                <th class="client-options">Thao tác</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

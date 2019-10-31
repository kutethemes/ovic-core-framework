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

@push( 'styles.table' )
    <style>
        .client-order {
            max-width: 45px;
            text-align: center;
        }

        .client-title,
        .client-desc {
            min-width: 200px;
        }
    </style>
@endpush

@push( 'scripts.table' )
    <script>
        $( '#table-posts' ).init_dataTable( "roles", {
            columns: [
                {
                    className: "client-order",
                    data: "ordering",
                    sortable: false
                },
                {
                    className: "client-name",
                    data: "name",
                    sortable: false
                },
                {
                    className: "client-title",
                    data: "title",
                    sortable: false
                },
                {
                    className: "client-desc",
                    data: "description",
                    sortable: false
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Nhóm không kích hoạt";
                        let _icon = "<span class='label label-danger'>Inactive</span>";

                        if ( data === 1 ) {
                            _class = "active";
                            _title = "Nhóm đang kích hoạt";
                            _icon = "<span class='label label-warning'>Active</span>";
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                }
            ]
        } );
    </script>
@endpush

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

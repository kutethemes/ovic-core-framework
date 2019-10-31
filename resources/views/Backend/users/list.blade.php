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

@push( 'scripts.table' )
    <script>
        $( '#table-posts' ).init_dataTable( "users", {
            columns: [
                {
                    className: "client-avatar",
                    data: "avatar_url",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return "<img alt='Ảnh đại diện' src='" + data + "'>";
                    }
                },
                {
                    className: "client-name",
                    data: "name",
                    sortable: false
                },
                {
                    className: "client-donvi",
                    data: "donvi_text",
                    sortable: false
                },
                {
                    className: "client-email",
                    data: "email",
                    sortable: false
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Người dùng không kích hoạt";
                        let _icon = "<span class='label label-danger'>Inactive</span>";

                        switch ( data ) {
                            case 1:
                                _class = "active";
                                _title = "Người dùng đang kích hoạt";
                                _icon = "<span class='label label-warning'>Active</span>";
                                break;
                            case 2:
                                _class = "inactive";
                                _title = "Người dùng ẩn";
                                _icon = "<span class='label label-warning'>Hidden</span>";
                                break;
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                },
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
            <th class="client-name">Tên hiển thị</th>
            <th class="client-donvi">Đơn vị</th>
            <th class="client-email">Email</th>
            <th class="client-status">Trạng thái</th>
        </tr>
        </thead>
    </table>
</div>

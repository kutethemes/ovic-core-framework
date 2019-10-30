@php
    /**
     * The table ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles.users' )
    <style>
        .client-order {
            max-width: 45px;
            text-align: center;
        }

        .client-icon {
            width: 20px;
            text-align: center;
        }
    </style>
@endpush

@push( 'scripts.users' )
    <script>
        $( '#table-posts' ).init_dataTable( "ucases", {
            columns: [
                {
                    className: "client-order",
                    data: "ordering",
                    sortable: false
                },
                {
                    className: "client-icon",
                    data: "router",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return "<span class='" + data.icon + "'></span>";
                    }
                },
                {
                    className: "client-title",
                    data: "title",
                    sortable: false
                },
                {
                    className: "client-module",
                    data: "router",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return data.module;
                    }
                },
                {
                    className: "client-controller",
                    data: "router",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return data.controller;
                    }
                },
                {
                    className: "client-description",
                    data: "router",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        return data.description;
                    }
                },
                {
                    className: "client-access",
                    data: "access",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        switch ( data ) {
                            case 1:
                                return "Backend";
                                break;
                            case 2:
                                return "Frontend";
                                break;
                        }
                        return "Public";
                    }
                },
                {
                    className: "client-status",
                    data: "status",
                    sortable: false,
                    render: function ( data, type, row, meta ) {
                        let _class = "inactive";
                        let _title = "Chức năng không kích hoạt";
                        let _icon = "<span class='label label-danger'>Inactive</span>";

                        switch ( data ) {
                            case 1:
                                _class = "active";
                                _title = "Chức năng đang kích hoạt";
                                _icon = "<span class='label label-warning'>Active</span>";
                                break;
                            case 2:
                                _class = "inactive";
                                _title = "Chức năng ẩn";
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
            <th class="client-order">Order</th>
            <th class="client-icon"></th>
            <th class="client-name">Tên chức năng</th>
            <th class="client-module">Module Name</th>
            <th class="client-controller">Controller</th>
            <th class="client-description">Descriptions</th>
            <th class="client-access">Access Level</th>
            <th class="client-status">Trạng thái</th>
        </tr>
        </thead>
    </table>
</div>

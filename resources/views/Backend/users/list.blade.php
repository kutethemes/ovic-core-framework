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

@push( 'scripts' )
    <!-- dataTables -->
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        var Table = $('#table-users').DataTable({
            processing: true,
            lengthChange: false,
            serverSide: true,
            dom: '<"head-table"fi>rt<"footer-table"p><"clear">',
            ajax: {
                url: "users/list",
                dataType: "json",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    let value  = '',
                        button = $('.btn-group.sorting .btn-primary');
                    if ( button.length ) {
                        value = button.val();
                    }
                    d.sorting = value;
                },
                error: function () {
                    swal({
                        type: 'error',
                        title: "Error!",
                        text: "Không tải được dữ liệu.",
                        showConfirmButton: true
                    });
                },
            },
            scrollX: true,
            columns: [
                {
                    className: "client-avatar",
                    data: "avatar_url",
                    sortable: false,
                    render: function (data, type, row, meta) {
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
                    render: function (data, type, row, meta) {
                        let _class = "inactive";
                        let _title = "Người dùng không kích hoạt";
                        let _icon  = "<span class='label label-danger'>Inactive</span>";

                        switch ( data ) {
                            case 1:
                                _class = "active";
                                _title = "Người dùng đang kích hoạt";
                                _icon  = "<span class='label label-warning'>Active</span>";
                                break;
                            case 2:
                                _class = "inactive";
                                _title = "Người dùng ẩn";
                                _icon  = "<span class='label label-warning'>Hidden</span>";
                                break;
                        }
                        return "<a href='#' title='" + _title + "' class='status " + _class + "'>" + _icon + "</a>";
                    }
                },
            ],
            createdRow: function (row, data, dataIndex) {
                // Set the data-status attribute, and add a class
                $(row).addClass('row-' + data.id);
            },
            language: {
                url: "{{ asset('datatable_language/vi.json') }}"
            }
        });
        $(document).on('click', '.btn-group.sorting button', function () {
            let button = $(this),
                value  = button.val();

            if ( !button.hasClass('btn-primary') ) {
                Table.column(1).search(value).draw();
                button.toggleClass('btn-primary btn-white');
                $('.btn-group.sorting button').not(button).removeClass('btn-primary').addClass('btn-white');
            } else {
                button.toggleClass('btn-primary btn-white');
                Table.column(1).search('').draw();
            }
        });
        $(window).on('resize', function () {
            Table.columns.adjust();
        });
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
    <table id="table-users" class="table table-striped table-hover" style="width:100%">
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
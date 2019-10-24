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

@push( 'styles' )
    <style>
        .client-order {
            width: 30px;
        }
    </style>
@endpush

@push( 'scripts' )
    <!-- dataTables -->
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        var Table = $('#table-roles').DataTable({
            processing: false,
            lengthChange: false,
            serverSide: true,
            dom: '<"head-table"fi>rt<"footer-table"p><"clear">',
            ajax: {
                url: "roles/list",
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
                    className: "client-options",
                    data: "status",
                    sortable: false
                }
            ],
            language: {
                sProcessing: "<div class=\"sk-spinner sk-spinner-double-bounce\">\n" +
                    "                                <div class=\"sk-double-bounce1\"></div>\n" +
                    "                                <div class=\"sk-double-bounce2\"></div>\n" +
                    "                            </div>",
                sLengthMenu: "Xem: _MENU_",
                sZeroRecords: "Không tìm thấy roles",
                sInfo: "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                sInfoEmpty: "Đang xem 0 đến 0 trong tổng số 0 mục",
                sInfoFiltered: "(được lọc từ _MAX_ mục)",
                sInfoPostFix: "",
                sSearch: "Tìm kiếm:",
                sUrl: "",
                oPaginate: {
                    sFirst: "Đầu",
                    sPrevious: "Trước",
                    sNext: "Tiếp",
                    sLast: "Cuối"
                }
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
            <button class="btn btn-white" type="button" value="0">
                Không kích hoạt
            </button>
        </div>
    </div>
</div>
<div class="clients-list">
    <table id="table-roles" class="table table-striped table-hover" style="width:100%">
        <thead>
        <tr>
            <th class="client-order">Order</th>
            <th class="client-name">Tên riêng</th>
            <th class="client-title">Tên hiển thị</th>
            <th class="client-desc">Mô tả</th>
            <th class="client-options">Thao tác</th>
        </tr>
        </thead>
    </table>
</div>
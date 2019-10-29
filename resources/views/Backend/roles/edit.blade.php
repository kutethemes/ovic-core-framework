@php
    /**
     * The edit role for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles' )
    <style>
        label.float-right {
            margin-bottom: 0;
        }
        div.client-detail {
            height: 575px;
        }
        .form-group.submit {
            margin-bottom: 0;
            margin-top: 1rem;
            text-align: right;
        }
    </style>
@endpush

@push( 'scripts' )
    <script>
        if ( !$.fn.serializeObject ) {
            $.fn.serializeObject = function () {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function () {
                    if ( o[ this.name ] ) {
                        if ( !o[ this.name ].push ) {
                            o[ this.name ] = [ o[ this.name ] ];
                        }
                        o[ this.name ].push(this.value || '');
                    } else {
                        o[ this.name ] = this.value || '';
                    }
                });
                return o;
            };
        }
        /* Edit */
        $(document).on('click', '#table-roles tbody > tr', function () {
            let row  = $(this),
                form = $('#edit-role'),
                role = Table.row(this).data();

            if ( !row.hasClass('active') ) {
                /* active */
                row.addClass('active').siblings().removeClass('active');

                $.each(role, function (index, value) {
                    if ( form.find('[name="' + index + '"]').length ) {
                        form.find('[name="' + index + '"]').val(value);
                    }
                });

                form.find('.form-group .add-role').addClass('d-none');
                form.find('.form-group .update-role,.form-group .remove-role').removeClass('d-none');

            } else {
                $('.wrapper-content .btn.add-new').trigger('click');
            }
        });
        /* Active/Deactive */
        $(document).on('click', '#table-roles .status', function () {
            let button  = $(this),
                tr      = button.closest('tr'),
                role    = Table.row(tr).data(),
                message = 'Tắt nhóm thành công';

            if ( role.status !== 1 ) {
                role.status = 1;
                message     = 'Kích hoạt nhóm thành công';
            } else {
                role.status = 0;
            }

            $.ajax({
                url: "roles/" + role.id,
                type: 'PUT',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: role.status,
                    dataTable: true
                },
                success: function (response) {

                    if ( response.status === 200 ) {

                        if ( Object.keys(response.data).length !== 0 ) {
                            Table.row(tr).data(response.data);
                        }

                        toastr.success(message);

                    } else {
                        let html = '';
                        $.each(response.message, function (index, value) {
                            html += "<p class='text-danger'>" + value + "</p>";
                        });

                        swal({
                            html: true,
                            type: 'error',
                            title: '',
                            text: html,
                            showConfirmButton: true
                        });
                    }
                },
            });

            return false;
        });
        /* Button action */
        $(document).on('click', '.wrapper-content .btn', function () {

            let button = $(this),
                form   = $('#edit-role'),
                roles  = $('#table-roles'),
                data   = form.serializeObject();

            if ( button.hasClass('add-new') ) {

                roles.find('tbody > tr').removeClass('active');
                form.trigger('reset');
                form.find('input[name="id"]').val('');
                form.find('.form-group .add-role').removeClass('d-none').siblings().addClass('d-none');

            } else if ( button.hasClass('add-role') ) {
                $.ajax({
                    url: "roles",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if ( response.status === 200 ) {

                            Table.ajax.reload(null, false);

                            toastr.success(response.message);

                        } else {

                            let html = '';
                            $.each(response.message, function (index, value) {
                                html += "<p class='text-danger'>" + value + "</p>";
                            });

                            swal({
                                html: true,
                                type: 'error',
                                title: '',
                                text: html,
                                showConfirmButton: true
                            });
                        }
                    },
                });
            } else if ( button.hasClass('remove-role') ) {
                swal({
                    title: "Bạn có chắc muốn xóa \"" + data.name + "\"?",
                    text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function (isConfirm) {
                    if ( isConfirm ) {
                        $.ajax({
                            url: "roles/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if ( response.status === 'success' ) {
                                    Table.ajax.reload(null, false);
                                }
                                swal({
                                    type: response.status,
                                    title: response.title,
                                    text: response.message,
                                    showConfirmButton: true,
                                });

                                $('.btn-primary.add-new').trigger('click');
                            },
                        });
                    }
                });
            } else if ( button.hasClass('update-role') ) {

                let tr         = roles.find('.row-' + data.id);
                data.dataTable = true;

                $.ajax({
                    url: "roles/" + data.id,
                    type: 'PUT',
                    dataType: 'json',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if ( response.status === 200 ) {

                            if ( Object.keys(response.data).length !== 0 ) {
                                Table.row(tr).data(response.data);
                            }

                            toastr.success(response.message);

                        } else {
                            let html = '';
                            $.each(response.message, function (index, value) {
                                html += "<p class='text-danger'>" + value + "</p>";
                            });

                            swal({
                                html: true,
                                type: 'error',
                                title: '',
                                text: html,
                                showConfirmButton: true
                            });
                        }
                    },
                });
            }

            return false;
        });
    </script>
@endpush

<form action="#" id="edit-role" method="post">
    <input type="hidden" name="id" value="">

    <div class="client-detail">
        <div class="full-height-scroll">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Name
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Tên riêng"
                               required="" aria-required="true" maxlength="150">
                        <span class="input-group-append">
                            <select name="status" class="btn btn-white dropdown-toggle">
                                <option value="1">Kích hoạt</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Title
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="title" class="form-control" placeholder="Tên hiển thị"
                               required="" aria-required="true" maxlength="150">
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Descriptions
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <textarea type="text" name="description" class="form-control" placeholder="Mô tả">
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Ordering
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" name="ordering" class="form-control" min="0" value="99"/>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

        </div>
    </div>

    <div class="form-group submit row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-danger remove-role d-none">
                <i class="fa fa-trash-o"></i>
                Xóa
            </button>
            <button class="btn btn-primary update-role d-none" type="button">
                <i class="fa fa-save"></i>
                Save change
            </button>
            <button class="btn btn-primary add-role" type="button">
                <i class="fa fa-upload"></i>
                Add role
            </button>
        </div>
    </div>
</form>
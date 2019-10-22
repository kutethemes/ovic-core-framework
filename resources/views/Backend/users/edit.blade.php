@php
    /**
     * The edit user for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push('styles')
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
        .avatar {
            display: inline-block;
        }
        .client-avatar img {
            max-width: 28px;
        }
        .avatar img {
            max-width: 96px;
        }
        img.img-thumbnail {
            border-width: 3px;
        }
        .img-thumbnail:hover {
            border-color: #23c6c8;
        }
        label.float-right {
            margin-bottom: 0;
        }
        div.client-detail {
            height: 605px;
        }
        .form-group.submit {
            margin-bottom: 0;
            margin-top: 1rem;
            text-align: right;
        }
        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }
        .field-password .input-group-append,
        .field-password-confirmation {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chosen -->
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>

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
        $('.chosen-select').chosen({
            width: "100%",
            no_results_text: "Oops, nothing found!",
            disable_search_threshold: 5
        });
        $(document).on('click', 'button.edit-field', function () {
            let group = $(this).closest('.input-group');
            let input = group.find('input');

            if ( input.attr('disabled') === undefined ) {
                input.attr('disabled', 'disabled').removeAttr('name');
            } else {
                input.removeAttr('disabled').attr('name', 'password');
            }
        });
        $(document).on('click', '#modal-media .btn-primary', function () {
            let file_box = $('#dropzone-previews .file-box.active');

            if ( file_box.length ) {
                let id        = file_box.data('id');
                let avatar_id = $('input[name="avatar"]');
                let avatar    = $('a[data-toggle="modal"]').find('img');
                let src       = file_box.find('img').attr('src');

                avatar_id.val(id).trigger('change');
                avatar.attr('src', src);
            }
        });
        $(document).on('click', '.wrapper-content .btn', function () {

            let button = $(this),
                form   = $('.edit-user');

            if ( button.hasClass('add-new') ) {

                form.trigger('reset');
                form.find('.field-password input').removeAttr('disabled').attr('name', 'password');
                form.find('.chosen-select').val('').trigger('chosen:updated');
                form.find('.field-password-confirmation').css('display', 'flex').find('input').attr('name', 'password_confirmation');
                form.find('.field-password .input-group-append').css('display', 'none');
                form.find('.form-group .add-user').removeClass('d-none').siblings().addClass('d-none');

            } else if ( button.hasClass('edit') ) {

                let chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ],
                    data   = button.parent().find('input').val();

                data = JSON.parse(data);

                form.find('.avatar img').attr('src', data.avatar_url);

                $.each(data, function (index, value) {
                    if ( form.find('[name="' + index + '"]').length ) {
                        if ( chosen.indexOf(index) !== -1 ) {
                            form.find('[name="' + index + '"]').val(value).trigger('chosen:updated');
                        } else if ( index === 'password' ) {
                            form.find('[name="' + index + '"]').val(value).attr('disabled', 'disabled').removeAttr('name');
                            form.find('[name="password_confirmation"]').removeAttr('name');
                        } else {
                            form.find('[name="' + index + '"]').val(value);
                        }
                    }
                });

                form.find('.form-group .add-user').addClass('d-none');
                form.find('.field-password-confirmation').css('display', 'none');
                form.find('.field-password .input-group-append').css('display', 'flex');
                form.find('.form-group .save-user,.form-group .remove-user').removeClass('d-none');

            } else if ( button.hasClass('lock') ) {

                let input   = button.parent().find('input');
                let data    = JSON.parse(input.val());
                let message = '';

                if ( data.status === 0 ) {
                    data.status = 1;
                    message     = 'Mở khóa thành công';
                } else {
                    data.status = 0;
                    message     = 'Khóa thành công';
                }

                $.ajax({
                    url: "users/" + data.id,
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        status: data.status
                    },
                    success: function (response) {

                        input.val(JSON.stringify(data));

                        toastr.success(message);

                        button.find('span').toggleClass('fa-lock fa-unlock-alt');
                    },
                });

            } else if ( button.hasClass('add-user') ) {
                button.find('span').addClass('loadding');
                $.ajax({
                    url: "users",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: button.closest('form').serializeObject(),
                    success: function (response) {

                        if ( response.status === 200 ) {

                            table.ajax.reload(null, false);

                            swal({
                                type: 'success',
                                title: 'Success!',
                                text: 'Tạo user thành công.',
                                showConfirmButton: true
                            });
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

                        button.find('span').removeClass('loadding');
                    },
                });
            } else if ( button.hasClass('remove-user') ) {
                swal({
                    title: "Bạn có chắc muốn xóa?",
                    text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function (isConfirm) {
                    if ( isConfirm ) {
                        let id = button.closest('form').find('input[name="id"]').val();
                        $.ajax({
                            url: "users/" + id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if ( response.status === 'success' ) {
                                    $('#users-table input[name="user-' + id + '"]').closest('tr').remove();
                                }
                                swal({
                                    type: response.status,
                                    title: response.title,
                                    text: response.message,
                                    showConfirmButton: true,
                                });
                            },
                        });
                    }
                });
            }

            return false;
        });
    </script>
@endpush

<form action="#" class="edit-user" method="post">
    <input type="hidden" name="id" value="">
    <input type="hidden" name="avatar" value="">

    <div class="row m-b-lg">
        <div class="col-lg-12 text-center">
            <a href="#" data-toggle="modal" data-target="#modal-media" class="avatar">
                <img alt="avatar" class="rounded-circle img-thumbnail" src="img/a_none.jpg">
            </a>
        </div>
    </div>
    <div class="client-detail">
        <div class="full-height-scroll">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Name
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Name"
                               required="" aria-required="true" maxlength="100">
                        <span class="input-group-append">
                            <select name="status" class="btn btn-white dropdown-toggle">
                                <option value="1">Kích hoạt</option>
                                <option value="2">Kích hoạt ẩn</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Email
                </label>
                <div class="col-sm-9">
                    <input type="email" name="email" class="form-control"
                           placeholder="Enter email"
                           required="" aria-required="true">
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group field-password row">
                <label class="col-sm-3 col-form-label">
                    Mật khẩu
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="password" class="form-control"
                               placeholder="Mật khẩu >= 8 ký tự"
                               name="password" aria-required="true" aria-invalid="false"
                               minlength="8">
                        <span class="input-group-append">
                            <button class="btn btn-info edit-field" type="button">
                                <i class="fa fa-paste"></i> Edit
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group field-password-confirmation row">
                <label class="col-sm-3 col-form-label">
                    Xác nhận
                </label>
                <div class="col-sm-9">
                    <input type="password" class="form-control"
                           placeholder="Mật khẩu >= 8 ký tự"
                           name="password_confirmation" aria-required="true" aria-invalid="false"
                           minlength="8">
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Đơn vị
                </label>
                <div class="col-sm-9">
                    <select name="donvi_id" class="form-control chosen-select"
                            data-placeholder="Chọn đơn vị">
                        <option value="">Chọn đơn vị</option>
                        <option value="1">Kích hoạt</option>
                        <option value="2">Kích hoạt ẩn</option>
                        <option value="0">Không kích hoạt</option>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Nhóm quyền
                </label>
                <div class="col-sm-9">
                    <select name="role_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn nhóm quyền">
                        <option value="1">Kích hoạt</option>
                        <option value="2">Kích hoạt ẩn</option>
                        <option value="0">Không kích hoạt</option>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Phạm vi quản lý
                </label>
                <div class="col-sm-9">
                    <select name="donvi_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn phạm vi quản lý">
                        <option value="1">Kích hoạt</option>
                        <option value="2">Kích hoạt ẩn</option>
                        <option value="0">Không kích hoạt</option>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

        </div>
    </div>

    <div class="form-group submit row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-danger remove-user d-none">
                <i class="fa fa-trash-o"></i>
                Xóa
            </button>
            <button class="btn btn-primary save-user d-none" type="button">
                <i class="fa fa-save"></i>
                Save change
            </button>
            <button class="btn btn-primary add-user" type="button">
                <i class="fa fa-upload"></i>
                Add user
                <span class="open-circle"></span>
            </button>
        </div>
    </div>
</form>
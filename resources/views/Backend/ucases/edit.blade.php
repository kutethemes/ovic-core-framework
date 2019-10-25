@php
    /**
     * The edit ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles' )
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

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
        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }
    </style>
@endpush

@push( 'scripts' )
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
        $(document).on('click', '#modal-media .btn-primary', function () {
            let file_box = $('#dropzone-previews .file-box.active');

            if ( file_box.length && file_box.find('img').length ) {
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
                form   = $('#edit-ucase'),
                ucases = $('#table-ucases'),
                data   = form.serializeObject();

            if ( button.hasClass('add-new') ) {

                form.trigger('reset');
                form.find('input[name="id"]').val('');
                form.find('input[name="avatar"]').val('0');
                form.find('.avatar img').attr('src', 'img/a_none.jpg');
                form.find('.chosen-select').val('').trigger('chosen:updated');
                form.find('.form-group .add-ucase').removeClass('d-none').siblings().addClass('d-none');

            } else if ( button.hasClass('edit') ) {

                let chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ],
                    ucase  = button.parent().find('input').val();

                ucase = JSON.parse(ucase);

                form.find('.avatar img').attr('src', ucase.avatar_url);

                $.each(ucase, function (index, value) {
                    if ( form.find('[name="' + index + '"]').length ) {
                        if ( chosen.indexOf(index) !== -1 ) {

                            value = JSON.parse(value);

                            if ( Array.isArray(value) ) {
                                value = value.map(Number);
                            }

                            form.find('[name="' + index + '"]').val(value).trigger('chosen:updated');
                        } else {
                            form.find('[name="' + index + '"]').val(value);
                        }
                    }
                });

                form.find('.form-group .add-ucase').addClass('d-none');
                form.find('.form-group .update-ucase,.form-group .remove-ucase').removeClass('d-none');

            } else if ( button.hasClass('lock') ) {

                let input   = button.parent().find('input');
                let ucase   = JSON.parse(input.val());
                let message = 'Khóa chức năng thành công';
                let txt     = 'Mở khóa chức năng';

                if ( ucase.status === 0 ) {
                    ucase.status = 1;
                    txt          = 'Khoá chức năng';
                    message      = 'Mở khóa chức năng thành công';
                } else {
                    ucase.status = 0;
                }

                $.ajax({
                    url: "ucases/" + ucase.id,
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: ucase.status
                    },
                    success: function (response) {

                        if ( response.status === 200 ) {

                            input.val(JSON.stringify(ucase));
                            button.attr('title', txt);
                            button.toggleClass('btn-warning btn-danger');
                            button.find('span').toggleClass('fa-lock fa-unlock-alt');

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

            } else if ( button.hasClass('add-ucase') ) {
                $.ajax({
                    url: "ucases",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if ( response.status === 200 ) {

                            Table.ajax.reload(null, false);

                            toastr.success('Tạo ucase thành công.');
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
            } else if ( button.hasClass('remove-ucase') ) {
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
                            url: "ucases/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if ( response.status === 'success' ) {
                                    ucases.find('input[name="ucase-' + data.id + '"]').closest('tr').remove();
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
            } else if ( button.hasClass('update-ucase') ) {
                $.ajax({
                    url: "ucases/" + data.id,
                    type: 'PUT',
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
            }

            return false;
        });
    </script>
@endpush

<form action="#" id="edit-ucases" method="post">
    <input type="hidden" name="id" value="">

    <div class="client-detail">

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Module Name
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="module" class="form-control" placeholder="Module Name"
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
                Quyền truy cập
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <select name="access" class="btn btn-white dropdown-toggle">
                        <option value="1">Backend</option>
                        <option value="2">Frontend</option>
                        <option value="0">Public</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Controller
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="controller" class="form-control" placeholder="Controller"
                           required="" aria-required="true" maxlength="150">
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Custom Link
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="custom_link" class="form-control" placeholder="Custom Link"
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
                Vị trí
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <select name="position" class="btn btn-white dropdown-toggle">
                        <option value="top">Top</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="button">Bottom</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Icon
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    @include( ovic_blade('Components.icon.icon'), [
                        'name'  =>'icon',
                        'value' =>'',
                    ] )
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

    <div class="form-group submit row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-danger remove-ucase d-none">
                <i class="fa fa-trash-o"></i>
                Xóa
            </button>
            <button class="btn btn-primary update-ucase d-none" type="button">
                <i class="fa fa-save"></i>
                Save change
            </button>
            <button class="btn btn-primary add-ucase" type="button">
                <i class="fa fa-upload"></i>
                Thêm chức năng
            </button>
        </div>
    </div>
</form>
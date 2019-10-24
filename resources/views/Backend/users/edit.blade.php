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

@push( 'styles' )
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
        div.modal-content {
            width: 100vw !important;
            height: 100vh !important;
        }
        div.inmodal .modal-header {
            padding: 10px;
        }
        .modal-footer {
            background-color: #fff;
        }
        .modal.show .modal-dialog {
            transform: none;
            max-width: inherit;
            margin: 0;
        }
        .file-box:hover::before {
            content: "";
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 2;
        }
        .file-box {
            position: relative;
            z-index: 3;
            cursor: pointer;
        }
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
        .field-password .input-group-append,
        .field-password-confirmation {
            display: none;
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
        $(document).on('click', '#dropzone-previews .file-box', function () {
            if ( $(this).find('img').length ) {
                $(this).addClass('active').siblings().removeClass('active');
            }
            return false;
        });
        $(document).on('click', '.wrapper-content .btn', function () {

            let button = $(this),
                form   = $('#edit-user'),
                users  = $('#table-users'),
                data   = form.serializeObject();

            if ( button.hasClass('add-new') ) {

                form.trigger('reset');
                form.find('input[name="id"]').val('');
                form.find('input[name="avatar"]').val('0');
                form.find('.avatar img').attr('src', 'img/a_none.jpg');
                form.find('.field-password input').removeAttr('disabled').attr('name', 'password');
                form.find('.chosen-select').val('').trigger('chosen:updated');
                form.find('.field-password-confirmation').css('display', 'flex').find('input').attr('name', 'password_confirmation');
                form.find('.field-password .input-group-append').css('display', 'none');
                form.find('.form-group .add-user').removeClass('d-none').siblings().addClass('d-none');

            } else if ( button.hasClass('edit') ) {

                let chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ],
                    user   = button.parent().find('input').val();

                user = JSON.parse(user);

                form.find('.avatar img').attr('src', user.avatar_url);

                $.each(user, function (index, value) {
                    if ( form.find('[name="' + index + '"]').length ) {
                        if ( chosen.indexOf(index) !== -1 ) {

                            value = JSON.parse(value);

                            if ( Array.isArray(value) ) {
                                value = value.map(Number);
                            }

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
                form.find('.form-group .update-user,.form-group .remove-user').removeClass('d-none');

            } else if ( button.hasClass('lock') ) {

                let input = button.parent().find('input');
                let user  = JSON.parse(input.val());
                let txt   = 'Mở khóa user';

                if ( user.status === 0 ) {
                    user.status = 1;
                    txt         = 'Khoá user';
                } else {
                    user.status = 0;
                }

                $.ajax({
                    url: "users/" + user.id,
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: user.status
                    },
                    success: function (response) {

                        if ( response.status === 200 ) {

                            input.val(JSON.stringify(user));
                            button.attr('title', txt);
                            button.toggleClass('btn-warning btn-danger');
                            button.find('span').toggleClass('fa-lock fa-unlock-alt');

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

            } else if ( button.hasClass('add-user') ) {
                $.ajax({
                    url: "users",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if ( response.status === 200 ) {

                            Table.ajax.reload(null, false);

                            toastr.success('Tạo user thành công.');
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
            } else if ( button.hasClass('remove-user') ) {
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
                            url: "users/" + data.id,
                            type: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if ( response.status === 'success' ) {
                                    users.find('input[name="user-' + data.id + '"]').closest('tr').remove();
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
            } else if ( button.hasClass('update-user') ) {
                $.ajax({
                    url: "users/" + data.id,
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

<form action="#" id="edit-user" method="post">
    <input type="hidden" name="id" value="">
    <input type="hidden" name="avatar" value="0">

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

            @if( Illuminate\Support\Facades\Schema::hasTable( 'donvi' ) )
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Đơn vị
                    </label>
                    <div class="col-sm-9">
                        <select name="donvi_id" class="form-control chosen-select"
                                data-placeholder="Chọn đơn vị">
                            <option value="0">Chọn đơn vị</option>
                            @php
                                $donvis = \Ovic\Framework\Donvi::all( [ 'id', 'tendonvi' ] )->toArray();
                            @endphp
                            @if( !empty( $donvis) )
                                @foreach ( $donvis as $donvi )
                                    <option value="{{ $donvi['id'] }}">{{ $donvi['tendonvi'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
            @endif

            @if( Illuminate\Support\Facades\Schema::hasTable( 'roles' ) )
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Nhóm quyền
                    </label>
                    <div class="col-sm-9">
                        <select name="role_ids" class="form-control chosen-select"
                                multiple="multiple" data-placeholder="Chọn nhóm quyền">
                            @php
                                $roles = \Ovic\Framework\Roles::all( [ 'id', 'title' ] )->toArray();
                            @endphp
                            @if( !empty( $roles) )
                                @foreach ( $roles as $role )
                                    <option value="{{ $role['id'] }}">{{ $role['title'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
            @endif

            @if( Illuminate\Support\Facades\Schema::hasTable( 'ucases' ) )
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Phạm vi quản lý
                    </label>
                    <div class="col-sm-9">
                        <select name="donvi_ids" class="form-control chosen-select"
                                multiple="multiple" data-placeholder="Chọn phạm vi quản lý">
                            @php
                                $ucases = \Ovic\Framework\Ucases::all( [ 'id', 'name' ] )->toArray();
                            @endphp
                            @if( !empty( $ucases ) )
                                @foreach ( $ucases as $ucase )
                                    <option value="{{ $ucase['id'] }}">{{ $ucase['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
            @endif

        </div>
    </div>

    <div class="form-group submit row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-danger remove-user d-none">
                <i class="fa fa-trash-o"></i>
                Xóa
            </button>
            <button class="btn btn-primary update-user d-none" type="button">
                <i class="fa fa-save"></i>
                Save change
            </button>
            <button class="btn btn-primary add-user" type="button">
                <i class="fa fa-upload"></i>
                Add user
            </button>
        </div>
    </div>
</form>

<div class="modal inmodal fade" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thư viện</h4>
            </div>
            <div class="modal-body">
                @include( ovic_blade('Backend.media.content') )
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Chọn ảnh</button>
            </div>
        </div>
    </div>
</div>
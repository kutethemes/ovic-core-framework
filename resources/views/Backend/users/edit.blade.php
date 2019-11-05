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

<form action="#" id="edit-post" method="post">
    <input type="hidden" name="id" value="">

    <div class="row m-b-lg">
        <div class="col-lg-12 text-center">
            @include( ovic_blade('Backend.media.field'), [
                'name'  =>  'avatar',
                'value' =>  '0',
                'alt'   =>  'Ảnh đại diện',
            ])
        </div>
    </div>
    <div class="client-detail">

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Tên hiển thị
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
        <div class="hr-line-dashed field-password-confirmation"></div>

        @if( !empty( $donvis) )
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Đơn vị
                </label>
                <div class="col-sm-9">
                    <select name="donvi_id" class="form-control chosen-select"
                            data-placeholder="Chọn đơn vị">
                        <option value="0">Chọn đơn vị</option>
                        @foreach ( $donvis as $donvi )
                            <option value="{{ $donvi['id'] }}">{{ $donvi['tendonvi'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        @endif

        @if( !empty( $roles) )
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Nhóm quyền
                </label>
                <div class="col-sm-9">
                    <select name="role_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn nhóm quyền">
                        <option value="0">Chọn nhóm quyền</option>
                        @foreach ( $roles as $role )
                            <option value="{{ $role['id'] }}">{{ $role['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        @endif

        @if( !empty( $ucases ) )
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Phạm vi quản lý
                </label>
                <div class="col-sm-9">
                    <select name="donvi_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn phạm vi quản lý">
                        <option value="0">Chọn phạm vi quản lý</option>
                        @foreach ( $ucases as $ucase )
                            <option value="{{ $ucase['id'] }}">{{ $ucase['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        @endif

    </div>

    <div class="form-group submit row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-danger remove-post d-none">
                <i class="fa fa-trash-o"></i>
                Xóa
            </button>
            <button class="btn btn-primary update-post d-none" type="button">
                <i class="fa fa-save"></i>
                Save change
            </button>
            <button class="btn btn-primary add-post" type="button">
                <i class="fa fa-upload"></i>
                Add user
            </button>
        </div>
    </div>
</form>

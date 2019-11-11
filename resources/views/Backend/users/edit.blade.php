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
            @include( name_blade('Backend.media.field'), [
                'name'  =>  'avatar',
                'value' =>  '0',
                'alt'   =>  'Ảnh đại diện',
            ])
        </div>
    </div>
    <div class="client-detail">

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Tên hiển thị *
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="name" class="form-control required" placeholder="Name" maxlength="100">
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
                Email *
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="email" name="email" class="form-control required"
                           placeholder="Enter email" maxlength="100">
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group field-password row">
            <label class="col-sm-3 col-form-label">
                Mật khẩu *
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="password" type="password" class="form-control required"
                           placeholder="Mật khẩu >= 8 ký tự"
                           name="password" minlength="8">
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
                Xác nhận *
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="password_confirmation" type="password" class="form-control required"
                           placeholder="Mật khẩu >= 8 ký tự"
                           name="password_confirmation" minlength="8">
                </div>
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

        @if( !empty( $donvis ) )
            <div class="form-group row d-none">
                <label class="col-sm-3 col-form-label">
                    Phạm vi quản lý
                </label>
                <div class="col-sm-9">
                    <select name="donvi_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn phạm vi quản lý">
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed d-none"></div>
        @endif

    </div>

    <div class="form-group submit row">
        <div class="col-sm-12">
            {{ button_set( 'delete', $permission, [ 'class'=>'btn btn-danger d-none' ] ) }}
            {{ button_set( 'edit', $permission, [ 'class'=>'btn btn-primary d-none' ] ) }}
            {{ button_set( 'add', $permission ) }}
        </div>
    </div>
</form>

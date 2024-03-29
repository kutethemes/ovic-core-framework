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
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" name="name" class="form-control required" placeholder="Họ tên" maxlength="100">
                    <span class="input-group-append">
                        <select name="status" class="custom-select rounded-0">
                            <option value="1">Kích hoạt</option>
                            <option value="2">Kích hoạt ẩn</option>
                            <option value="0">Không kích hoạt</option>
                        </select>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="email" name="email" class="form-control required"
                           placeholder="Enter email" maxlength="100">
                </div>
            </div>
        </div>

        <div class="form-group field-password row">
            <div class="col-sm-12">
                <div class="input-group">
                    <input id="password" type="password" class="form-control required"
                           placeholder="Mật khẩu >= 8 ký tự"
                           name="password" minlength="8">
                    <span class="input-group-append">
                        <button class="btn btn-info edit-field" type="button">
                            <i class="fa fa-paste"></i> Sửa
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group field-password-confirmation row">
            <div class="col-sm-12">
                <div class="input-group">
                    <input id="password_confirmation" type="password" class="form-control required"
                           placeholder="Mật khẩu >= 8 ký tự"
                           name="password_confirmation" minlength="8">
                </div>
            </div>
        </div>

        @if( !empty( $donvis) )
            <div class="form-group row donvi">
                <div class="col-sm-12">
                    <select name="donvi_id" class="form-control chosen-select"
                            data-placeholder="Chọn đơn vị">
                        <option value="0"></option>
                        {!!
                            _menu_tree( $donvis, [
                               'title'  =>  'tendonvi',
                               'type'   =>  'dropdown',
                            ]);
                        !!}
                    </select>
                </div>
            </div>
        @endif

        @if( !empty( $roles) )
            <div class="form-group row">
                <div class="col-sm-12">
                    <select name="role_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn nhóm quyền">
                        <option value="0"></option>
                        @foreach ( $roles as $role )
                            <option value="{{ $role->id }}">{{ $role->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if( !empty( $donvis ) )
            <div class="form-group row phamvi">
                <div class="col-sm-12">
                    <select name="donvi_ids" class="form-control chosen-select"
                            multiple="multiple" data-placeholder="Chọn phạm vi quản lý">
                        <option value="0"></option>
                        {!!
                            _menu_tree( $donvis, [
                               'title'  =>  'tendonvi',
                               'type'   =>  'dropdown',
                            ]);
                        !!}
                    </select>
                </div>
            </div>
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

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

@push( 'styles.table' )
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
        .client-avatar img {
            max-width: 28px;
        }

        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }

        .field-password .input-group-append {
            display: none;
        }
    </style>
@endpush

@push( 'scripts.table' )
    <!-- Chosen -->
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script>
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Oops, nothing found!",
            disable_search_threshold: 5
        } );
        $( document ).on( 'click', 'button.edit-field', function () {
            let group = $( this ).closest( '.input-group' );
            let input = group.find( 'input' );

            if ( input.attr( 'disabled' ) === undefined ) {
                input.attr( 'disabled', 'disabled' ).removeAttr( 'name' );
            } else {
                input.removeAttr( 'disabled' ).attr( 'name', 'password' );
            }
        } );
        /* Edit */
        $( document ).on( 'click', '#table-posts tbody > tr', function () {
            let row = $( this ),
                form = $( '#edit-post' ),
                user = OvicTable.row( this ).data(),
                chosen = [ 'role_ids', 'donvi_ids', 'donvi_id' ];

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );
                form.find( '.ovic-field-image img' ).attr( 'src', user.avatar_url );

                $.each( user, function ( index, value ) {
                    if ( form.find( '[name="' + index + '"]' ).length ) {
                        if ( chosen.indexOf( index ) !== -1 ) {

                            value = JSON.parse( value );

                            if ( Array.isArray( value ) ) {
                                value = value.map( Number );
                            }

                            form.find( '[name="' + index + '"]' ).val( value ).trigger( 'chosen:updated' );
                        } else if ( index === 'password' ) {
                            form.find( '[name="' + index + '"]' ).val( value ).attr( 'disabled', 'disabled' ).removeAttr( 'name' ).trigger( 'change' );
                            form.find( '[name="password_confirmation"]' ).removeAttr( 'name' );
                        } else {
                            form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );
                        }
                    }
                } );

                form.find( '.form-group .add-post' ).addClass( 'd-none' );
                form.find( '.field-password-confirmation' ).css( 'display', 'none' );
                form.find( '.field-password .input-group-append' ).css( 'display', 'flex' );
                form.find( '.form-group .update-post,.form-group .remove-post' ).removeClass( 'd-none' );
            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "users",
                "Tắt kích hoạt thành công",
                "Kích hoạt thành công"
            );

            return false;
        } );
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form = $( '#edit-post' ),
                table = $( '#table-posts' );

            table.find( 'tbody > tr' ).removeClass( 'active' );
            form.trigger( 'reset' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( '.ovic-field-image .ovic-image-remove' ).trigger( 'click' );
            form.find( '.field-password input' ).removeAttr( 'disabled' ).attr( 'name', 'password' );
            form.find( '.chosen-select' ).val( '' ).trigger( 'chosen:updated' );
            form.find( '.field-password-confirmation' ).css( 'display', 'flex' ).find( 'input' ).attr( 'name', 'password_confirmation' );
            form.find( '.field-password .input-group-append' ).css( 'display', 'none' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            return false;
        } );
        /* Add post */
        $( document ).on( 'click', '.wrapper-content .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "users", data );

            return false;
        } );
        /* Update post */
        $( document ).on( 'click', '.wrapper-content .btn.update-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            data.dataTable = true;

            button.update_post( "users", data, "#table-posts" );

            return false;
        } );
        /* Remove post */
        $( document ).on( 'click', '.wrapper-content .btn.remove-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.remove_post( "users", data );

            return false;
        } );
    </script>
@endpush

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

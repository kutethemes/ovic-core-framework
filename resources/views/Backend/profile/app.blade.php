@php
    /**
     * The profile for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', 'THÔNG TIN CÁ NHÂN' )

@push( 'styles' )
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Chosen --}}
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    {{--  Date picker  --}}
    <link rel="stylesheet" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
    <style>
        .client-avatar img {
            max-width: 28px;
        }

        .chosen-container {
            font-size: 0.9rem;
        }

        .datepicker.dropdown-menu {
            padding: 5px;
        }

        .datepicker.dropdown-menu th,
        .datepicker.dropdown-menu td {
            padding: 4px 5px !important;
        }
    </style>
@endpush

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox normal-scroll-content">
            <div class="ibox-content">
                <form action="#" id="edit-post" method="post">
                    <input type="hidden" name="id" value="{{ $user->id }}">

                    <div class="row">
                        <div class="col-md-6 b-r">

                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    @include( name_blade('Backend.media.field'), [
                                        'name'  =>  'avatar',
                                        'value' =>  $user->avatar,
                                        'alt'   =>  'Ảnh đại diện',
                                    ])
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tên hiển thị</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" name="name" class="form-control required"
                                               placeholder="Họ tên"
                                               maxlength="100" value="{{ $user->name }}">
                                        <div class="input-group-append">
                                            @switch( $user->status )
                                                @case( 0 )
                                                <button class="btn btn-danger rounded-0" type="button">
                                                    KHÔNG KÍCH HOẠT
                                                </button>
                                                @break

                                                @case( 2 )
                                                <button class="btn btn-warning rounded-0" type="button">
                                                    KÍCH HOẠT ẨN
                                                </button>
                                                @break

                                                @default
                                                <button class="btn btn-primary rounded-0" type="button">
                                                    ĐANG KÍCH HOẠT
                                                </button>
                                                @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control required"
                                           placeholder="Enter email" maxlength="100" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group field-password row">
                                <label class="col-sm-2 col-form-label">Mật khẩu</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control required"
                                               placeholder="Mật khẩu >= 8 ký tự"
                                               name="password" minlength="8" disabled
                                               value="{{ $user->password }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-info edit-field rounded-0" type="button">
                                                <i class="fa fa-paste"></i> Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Họ và tên</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" name="hodem" class="form-control" placeholder="Họ đệm"
                                               value="{{ $canhan['hodem'] }}">
                                        <span class="input-group-append">
                                            <input type="text" name="ten" class="form-control" placeholder="Tên"
                                                   value="{{ $canhan['ten'] }}">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ngày sinh</label>
                                <div class="col-sm-10">
                                    <input type="text" name="ngaysinh" class="form-control"
                                           value="{{ $canhan['ngaysinh'] }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Số CMND</label>
                                <div class="col-sm-10">
                                    <input type="text" name="scmnd" class="form-control" value="{{ $canhan['scmnd'] }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nơi sinh</label>
                                <div class="col-sm-10">
                                    <select name="noisinh" id="noisinh" class="form-control chosen-select">
                                        @foreach( $diachi as $item )
                                            <option value="{{ $item->id }}"
                                                    @if( $canhan['noisinh'] == $item->id ) selected @endif>
                                                {{ $item->tendiadanh }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Giới tính</label>
                                <div class="col-sm-10">
                                    <div class="radio radio-info form-check-inline col-form-label">
                                        <input type="radio" id="gioitinh-nam" value="1" name="gioitinh"
                                               @if( $canhan['gioitinh'] == 1 ) checked @endif>
                                        <label for="gioitinh-nam" class="m-0">
                                            Nam
                                        </label>
                                    </div>
                                    <div class="radio radio-info form-check-inline col-form-label">
                                        <input type="radio" id="gioitinh-nu" value="0" name="gioitinh"
                                               @if( $canhan['gioitinh'] == 0 ) checked @endif>
                                        <label for="gioitinh-nu" class="m-0">
                                            Nữ
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                        </div>
                    </div>

                    <div class="form-group submit row">
                        <div class="col-sm-12">
                            {{ button_set( 'delete', $permission, [ 'class'=>'btn btn-danger' ] ) }}
                            {{ button_set( 'edit', $permission, [ 'class'=>'btn btn-primary' ] ) }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( name_blade('Backend.media.modal') )

@endpush

@push( 'scripts' )
    {{-- Sweet Alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{--  Date picker  --}}
    <script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script>
        toastr.options = {
            "preventDuplicates": true,
        };
        if ( !$.fn.serializeObject ) {
            $.fn.serializeObject = function () {
                var o = {};
                var a = this.serializeArray();
                $.each( a, function () {
                    if ( o[this.name] ) {
                        if ( !o[this.name].push ) {
                            o[this.name] = [ o[this.name] ];
                        }
                        o[this.name].push( this.value || '' );
                    } else {
                        o[this.name] = this.value || '';
                    }
                } );
                return o;
            };
        }
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Không tìm thấy kết quả",
            disable_search_threshold: 5
        } );
        $( 'input[name=ngaysinh]' ).datepicker( {
            format: 'dd/mm/yyyy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        } );
        $( document ).on( 'click', 'button.edit-field', function () {
            let group = $( this ).closest( '.input-group' ),
                input = group.find( 'input' );

            if ( input.attr( 'disabled' ) === undefined ) {
                input.attr( 'disabled', 'disabled' ).removeAttr( 'name' );
            } else {
                input.removeAttr( 'disabled' ).attr( 'name', 'password' );
            }
        } );
        $( document ).on( 'click', 'button.edit-post', function () {
            let button = $( this ),
                form   = button.closest( 'form' ),
                data   = form.serializeObject();

            $.ajax( {
                url: "profile/" + data.id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {

                    if ( response.status === 200 ) {

                        toastr.info( response.message );

                    } else if ( response.status === 400 ) {

                        let html = '';
                        $.each( response.message, function ( index, value ) {
                            html += "<p class='text-danger'>" + value + "</p>";
                        } );

                        swal( {
                            html: true,
                            type: 'error',
                            title: '',
                            text: html,
                            showConfirmButton: true
                        } );
                    }
                },
                error: function ( response ) {
                    swal( {
                        type: 'error',
                        title: "Error!",
                        text: "Hệ thống không phản hồi.",
                        showConfirmButton: true
                    } );
                },
            } );
        } );
        $( document ).on( 'click', 'button.delete-post', function () {
            let button = $( this ),
                form   = button.closest( 'form' ),
                data   = form.serializeObject();

            swal( {
                title: "Bạn có chắc muốn xóa hồ sơ?",
                text: "Khi đồng ý xóa hồ sơ của bạn sẽ không thể khôi phục lại!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Đồng ý",
                cancelButtonText: "Hủy",
                closeOnConfirm: false
            }, function ( isConfirm ) {
                if ( isConfirm ) {

                    $.ajax( {
                        url: "profile/" + data.id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        success: function ( response ) {

                            swal( {
                                type: response.status,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                            }, function ( isConfirm ) {
                                if ( isConfirm ) {
                                    document.getElementById( 'logout-form' ).submit();
                                }
                            } );
                        },
                        error: function () {
                            swal( {
                                type: 'error',
                                title: "Error!",
                                text: "Hệ thống không phản hồi.",
                                showConfirmButton: true
                            } );
                        },
                    } );
                }
            } );
        } );
    </script>
@endpush

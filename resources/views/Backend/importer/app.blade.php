@php
    /**
     * The importer for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', 'IMPORT DỮ LIỆU' )

@push('styles')
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Chosen --}}
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    <style>
        #page-wrapper .ibox-content {
            background: transparent;
            border: none;
            padding-left: 0;
            padding-right: 0;
        }

        .chosen-container-single .chosen-single {
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #495057;
        }
    </style>
@endpush

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">

                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a class="nav-link active" data-toggle="tab" href="#tab-1">
                                <i class="fa fa-download"></i>
                                Export người dùng
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-2">
                                <i class="fa fa-upload"></i>
                                Import người dùng
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-1" class="tab-pane active">
                            <div class="panel-body">

                                <form action="" id="export-data">
                                    <input type="hidden" name="type" value="export">
                                    <input type="hidden" name="target" value="user">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            Tên file
                                        </label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="fileName"
                                                   value="danh_sach_nguoi_dung">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            Trạng thái
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="status" class="custom-select rounded-0">
                                                <option value="">Tất cả</option>
                                                <option value="1">Đang kích hoạt</option>
                                                <option value="2">Đang ẩn</option>
                                                <option value="0">Không kích hoạt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            Đơn vị
                                            <br>
                                            <small class="text-navy">
                                                Những người dùng trong đơn vị được chọn quản lý.
                                            </small>
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="donvi" class="chosen-select" data-placeholder="Tất cả">
                                                <option value=""></option>
                                                {!!
                                                    _menu_tree( $donvi, [
                                                       'title'  =>  'tendonvi',
                                                       'type'   =>  'dropdown',
                                                    ]);
                                                !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group submit row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">
                                                Export
                                            </button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div role="tabpanel" id="tab-2" class="tab-pane">
                            <div class="panel-body">

                                <form action="" id="import-data">
                                    <input type="hidden" name="type" value="import">
                                    <input type="hidden" name="target" value="user">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            File import
                                            <br>
                                            <small class="text-navy">
                                                Chỉ hỗ trợ file excel.
                                            </small>
                                        </label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="file" value="">
                                                <span class="input-group-append ovic-field-image">
                                                    <a href="#" class="btn btn-primary ovic-image-add">
                                                        Add File
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            Chọn nhóm quyền
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="role" class="chosen-select" multiple
                                                    data-placeholder="Chọn nhóm quyền">
                                                <option value=""></option>
                                                @foreach( $role as $data )
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">
                                            Chọn đơn vị
                                            <br>
                                            <small class="text-navy">
                                                Những người dùng trong đơn vị được chọn quản lý.
                                            </small>
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="donvi" class="chosen-select">
                                                {!!
                                                    _menu_tree( $donvi, [
                                                       'title'  =>  'tendonvi',
                                                       'type'   =>  'dropdown',
                                                    ]);
                                                !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group submit row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">
                                                Import
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( name_blade('Backend.media.modal'), [
        'text_save' => 'Chọn file'
    ] )

@endpush

@push('scripts')
    {{-- Sweet Alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script>
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
        toastr.options = {
            "preventDuplicates": true,
        };
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Không tìm thấy kết quả!",
            disable_search_threshold: 5,
            allow_single_deselect: true
        } );
        $( document ).on( 'click', '#modal-media .save-modal', function () {
            let ids   = [],
                html  = '',
                form  = $( '#import-data' ),
                media = $( '#modal-media' ),
                file  = media.find( '.content-previews .file-box.active' );


            form.find( '.form-group input[name="file"]' ).val( file.data( 'name' ) );

            return false;
        } );
        $( document ).on( 'submit', '#export-data', function () {
            let form = $( this ),
                data = form.serializeObject();

            window.location = "{{ asset('importer/create') }}?" + $.param( data );

            return false;
        } );
        $( document ).on( 'submit', '#import-data', function () {
            let form = $( this ),
                data = form.serializeObject();

            $.ajax( {
                url: "{{ asset('importer/create') }}",
                dataType: "json",
                type: "GET",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {
                    if ( response.status === 200 ) {
                        swal( {
                            type: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: true
                        } );
                    } else {
                        swal( {
                            type: 'error',
                            title: 'Error!',
                            text: response.message,
                            showConfirmButton: true
                        } );
                    }
                },
                error: function () {
                    swal( {
                        type: 'error',
                        title: 'Error!',
                        text: 'Import không thành công',
                        showConfirmButton: true
                    } );
                }
            } );

            return false;
        } );
    </script>
@endpush

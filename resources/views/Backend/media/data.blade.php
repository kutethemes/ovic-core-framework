@php
    /**
     * The content file for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@php
    $limit          = ( isset( $limit ) ) ? $limit : 18;
    $offset         = ( isset( $offset ) ) ? $offset : 0;
    $inputOffset    = $limit;
    $check          = ( isset( $multi ) ) ? $multi : true;
    $multiFile      = ( isset( $multi_file ) ) ? $multi_file : false;
@endphp

@push( 'styles' )
    {{-- dropzone --}}
    <link href="{{ asset('css/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet">
    {{-- jsTree --}}
    <link href="{{ asset('css/plugins/jsTree/style.min.css') }}" rel="stylesheet">
    {{-- Awesome Bootstrap Checkbox --}}
    <link href="{{ asset('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    {{-- style media --}}
    <style>
        .checkbox.checkbox-danger {
            padding-left: 0;
            float: left;
        }

        .checkbox label::after {
            top: 1px;
            left: 3px;
        }

        .checkbox label::before {
            top: 2px;
            left: 4px;
        }

        .file .image {
            text-align: center;
        }

        .btn-del-file {
            font-size: 20px;
            line-height: 1;
            padding: 10px;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
            color: red;
            z-index: 2;
        }

        .file-box:hover .btn-del-file {
            display: inline-block;
        }

        .file-name .name {
            white-space: nowrap;
            overflow: hidden;
            display: block;
        }

        .control-filter .file-control {
            background: none;
            border: none;
            margin: 0;
        }

        .file-box a {
            line-height: 1;
        }

        .list-style .icon,
        .list-style .image {
            display: none;
        }

        .list-style .btn-del-file {
            right: 5px;
            left: auto;
            top: auto;
            bottom: -5px;
        }

        .switch {
            float: right;
        }

        form.dropzone {
            margin-bottom: 15px;
        }

        .switch .onoffswitch-inner::before {
            content: "LIST";
        }

        .switch .onoffswitch-inner::after {
            content: "GRID";
        }

        .file-box .btn-circle {
            position: absolute;
            right: 10px;
            top: 5px;
            width: 25px;
            height: 25px;
            padding: 3px 0;
            display: none;
            z-index: 1;
        }

        .file-box.active .btn-circle {
            display: inline-block;
        }

        .file-box.active a:not(.btn-del-file)::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .content-previews {
            margin-left: -5px;
            margin-right: -5px;
        }

        .content-previews .file-box {
            position: relative;
            z-index: 3;
            cursor: pointer;
            width: 19.5%;
            float: none;
            padding-left: 5px;
            padding-right: 5px;
            display: inline-block;
        }

        .content-previews .file-box > .file {
            margin-right: 0;
        }

        button.btn-del-select {
            float: right;
            display: none;
        }

        #dropzone-previews {
            position: relative;
        }

        #dropzone-previews.loading .sk-spinner.sk-spinner-double-bounce {
            display: block;
        }

        .sk-spinner.sk-spinner-double-bounce {
            display: none;
            position: absolute;
            top: calc(50% - 20px);
            left: 0;
            right: 0;
            z-index: 10;
        }

        @media (max-width: 1440px) {
            .content-previews .file-box {
                width: 24.5%;
            }
        }

        @media (max-width: 768px) {
            .file-box {
                width: 200px;
            }
        }

        @media (max-width: 767px) {
            .content-image {
                margin-top: 20px;
            }
        }

        @media (max-width: 680px) {
            .content-previews .file-box {
                width: 32.5%;
            }
        }

        @media (max-width: 480px) {
            .content-previews .file-box {
                width: 49.5%;
            }
        }

        @media (max-width: 376px) {
            .content-previews .file-box {
                width: 100%;
            }
        }
    </style>
@endpush

@push( 'scripts' )
    {{-- dropzone --}}
    <script src="{{ asset('js/plugins/dropzone/dropzone.min.js') }}"></script>
    {{-- jsTree --}}
    <script src="{{ asset('js/plugins/jsTree/jstree.min.js') }}"></script>
    {{-- script media --}}
    <script>
        var treeEl = $( '#mytree' ),
            dropzonePreviews = $( '#dropzone-previews' ),
            treeFolder = function ( directories, reload = false ) {
                if ( reload ) {
                    treeEl.jstree( true ).settings.core.data = JSON.parse( directories );
                    treeEl.jstree( true ).refresh();
                } else {
                    treeEl.jstree( {
                        "core": {
                            "data": JSON.parse( directories )
                        },
                    } );
                }
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

        @if( user_can( 'add', 'upload' ) )
        /* Tạo file */
        Dropzone.options.dropzoneForm = {
            url: "upload",
            headers: {
                'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
            },
            acceptedFiles: '.zip,.rar,audio/*,video/*,image/*,.doc,.docx,.xls,.xlsx,application/pdf',
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 16, // MB
            uploadMultiple: false,
            dictFileTooBig: 'File lớn hơn 16MB',
            init: function () {
                this.on( "complete", function ( file ) {
                    this.removeFile( file );
                } );
            },
            success: function ( file, response ) {

                $( '#dropzone-previews .content-previews' ).prepend( response.html );

                if ( response.status == 'success' ) {
                    toastr.info( response.message );

                    /* Tạo thư mục */
                    treeFolder( response.directories, true );
                } else {
                    swal( {
                        type: 'error',
                        title: 'Error!',
                        text: response.message,
                        showConfirmButton: true
                    } );
                }
            },
            dictDefaultMessage: "<strong>Kéo thả files vào đây để upload lên máy chủ. </strong></br>  (Hoặc click chuột để chọn files upload.)"
        };
        @endif

        @if( user_can( 'delete', 'upload' ) )
        /* Xóa file */
        $( document ).on( 'click', '.btn-del-file', function () {
            let parent = $( this ).closest( '.file-box' );
            let id = parent.data( 'id' );
            swal( {
                title: "Bạn có chắc muốn xóa?",
                text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function ( isConfirm ) {
                if ( isConfirm ) {
                    $.ajax( {
                        url: "upload/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        success: function ( response ) {
                            if ( response.status === 200 ) {
                                /* Tạo thư mục */
                                treeFolder( response.directories, true );

                                parent.remove();

                                swal( {
                                    type: 'success',
                                    title: 'Deleted!',
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
                    } );
                }
            } );

            return false;
        } );
        /* Xóa nhiều file */
        $( document ).on( 'selected_images', function ( event ) {
            let input = $( event.target ),
                ids = input.val(),
                button = $( '.btn-del-select' );

            if ( ids !== 0 && ids !== '' ) {
                button.css( 'display', 'block' );
            } else {
                button.css( 'display', 'none' );
            }
        } );
        $( document ).on( 'click', '.btn-del-select', function () {
            let button = $( this ),
                content = dropzonePreviews,
                input = content.find( 'input[name="images"]' ),
                ids = input.val();

            swal( {
                title: "Bạn có chắc muốn xóa?",
                text: "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục lại!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function ( isConfirm ) {
                if ( isConfirm ) {
                    $.ajax( {
                        url: "upload/" + ids,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                        },
                        success: function ( response ) {
                            if ( response.status === 200 ) {
                                /* Tạo thư mục */
                                treeFolder( response.directories, true );

                                $.each( response.ids, function ( index, value ) {
                                    content.find( '.image-' + value ).remove();
                                } );
                                swal( {
                                    type: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: true
                                } );
                                input.val( 0 );
                                button.css( 'display', 'none' );
                            } else {
                                swal( {
                                    type: 'error',
                                    title: 'Error!',
                                    text: response.message,
                                    showConfirmButton: true
                                } );
                            }
                        },
                    } );
                }
            } );
        } );
        @endif
        /* Lọc */
        $( document ).on( 'submit', '.filter-control', function () {
            let form = $( this ),
                offset = form.find( '[name="offset"]' ),
                limit = form.find( '[name="limit"]' );

            offset.val( "{{ $offset }}" );

            $.ajax( {
                url: "upload/create",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                data: {
                    form: $( this ).serializeObject(),
                    filter: true
                },
                success: function ( response ) {

                    offset.val( parseInt( limit.val() ) + parseInt( offset.val() ) );

                    $( '#dropzone-previews .content-previews' ).html( response.html );

                    toastr[response.status]( response.message );
                },
            } );
            return false;
        } );
        /* Reset */
        $( document ).on( 'click', '.reset-filter', function () {
            $( 'input[name="s"]' ).val( '' );
            $( 'input[name="dir"]' ).val( '' );
            $( 'input[name="sort"]' ).val( 'all' );
            $( 'input[name="offset"]' ).val( "{{ $offset }}" );
            $( 'button[value="all"]' ).addClass( 'active' ).siblings().removeClass( 'active' );
            treeEl.find( 'a' ).removeClass( 'jstree-clicked' );
        } );
        /* Lọc kiểu file */
        $( document ).on( 'click', '.file-control', function () {
            $( 'input[name="sort"]' ).val(
                $( this ).val()
            ).trigger( 'change' );
            $( this ).addClass( 'active' ).siblings().removeClass( 'active' );
        } );
        /* Lọc theo ngày */
        $( document ).on( 'click', '.dir-filter', function () {
            let parent = $( this ).closest( '.folder-list' );
            $( 'input[name="dir"]' ).val(
                $( this ).data( 'dir' )
            ).trigger( 'change' );

            $( this ).closest( 'form' ).trigger( 'submit' );

            return false;
        } );
        /* Tải thêm ảnh */
        $( document ).on( 'click', '.load_more', function () {
            let button = $( this ),
                form = button.closest( 'form' ),
                offset = form.find( '[name="offset"]' ),
                limit = form.find( '[name="limit"]' );

            $.ajax( {
                url: "upload/create",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                data: {
                    form: form.serializeObject(),
                    filter: true
                },
                success: function ( response ) {
                    if ( response.count > 0 ) {

                        offset.val( parseInt( limit.val() ) + parseInt( offset.val() ) );

                        $( '#dropzone-previews .content-previews' ).append( response.html );
                    }
                    toastr[response.status]( response.message );
                },
            } );
            return false;
        } );
        /* Sắp xếp */
        $( document ).on( 'click', '.onoffswitch-label', function () {
            dropzonePreviews.toggleClass( 'list-style' );
        } );
        /* Chọn ảnh */
        $( document ).on( 'click', '.checkbox.checkbox-danger input', function () {
            if ( $( this ).is( ':checked' ) ) {
                dropzonePreviews.addClass( 'select-image' );
            } else {
                dropzonePreviews.removeClass( 'select-image' );
                dropzonePreviews.find( '.file-box.active' ).removeClass( 'active' );
                dropzonePreviews.find( 'input[name="images"]' ).val( '' ).trigger( 'selected_images' );
            }
        } );
        $( document ).on( 'click', '#dropzone-previews.select-image .file-box', function () {
            let file = $( this ),
                ids = [],
                id = file.data( 'id' ),
                form = file.closest( '#dropzone-previews' ),
                input = form.find( 'input[name="images"]' );

            if ( form.hasClass( 'multi' ) ) {
                file.toggleClass( 'active' );
                form.find( '.file-box' ).each( function () {
                    let file = $( this ),
                        id = file.data( 'id' );
                    if ( file.hasClass( 'active' ) ) {
                        ids.push( id );
                    }
                } );
                if ( ids.length > 0 ) {
                    input.val( ids.join( ',' ) );
                } else {
                    input.val( '' );
                }
                input.trigger( 'selected_images' );
            } else {
                if ( file.hasClass( 'active' ) ) {
                    file.removeClass( 'active' );
                    input.val( 0 );
                } else {
                    input.val( id );
                    file.addClass( 'active' ).siblings().removeClass( 'active' );
                }
            }

            return false;
        } );
        @if( !empty($directories) )
        /* Tạo thư mục */
        treeFolder( '{!! $directories !!}' );
        @endif
    </script>
@endpush

<div class="col-xl-3 col-md-4 col-sm-5 full-height filter-image">
    <div class="ibox full-height-scroll">
        <div class="ibox-content">
            @if( $check == true )
                <div class="checkbox checkbox-danger">
                    <input id="checkbox6" type="checkbox">
                    <label for="checkbox6">
                        Chọn nhiều ảnh
                    </label>
                </div>
            @endif
            <div class="switch">
                <div class="onoffswitch">
                    <input type="checkbox" checked="" class="onoffswitch-checkbox" id="example1">
                    <label class="onoffswitch-label" for="example1">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>

            @if( user_can( 'add', 'upload' ) )
                <form action="upload" class="dropzone" id="dropzoneForm" method="POST"
                      enctype="multipart/form-data">
                    <div class="fallback">
                        <input name="file" type="file" multiple/>
                    </div>
                </form>
            @endif

            <form method="post" class="file-manager filter-control">
                <input type="hidden" name="dir" value=""/>
                <input type="hidden" name="sort" value="all"/>
                <input type="hidden" name="limit" value="{{ $limit }}"/>
                <input type="hidden" name="offset" value="{{ $inputOffset }}"/>

                <div class="input-group">
                    <input type="text" name="s" class="form-control"/>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </span>
                </div>
                <div class="hr-line-dashed"></div>
                <h5>Show:</h5>
                <div class="control-filter">
                    <button type="submit" value="all" class="file-control active">All</button>
                    <button type="submit" value="doc" class="file-control">Documents</button>
                    <button type="submit" value="au" class="file-control">Audio</button>
                    <button type="submit" value="vi" class="file-control">Video</button>
                    <button type="submit" value="im" class="file-control">Images</button>
                    <button type="submit" value="ar" class="file-control">Archive</button>
                </div>
                <div class="hr-line-dashed"></div>
                <h5>Thư mục</h5>
                <div id="mytree"></div>
                <div class="hr-line-dashed"></div>
                <div class="clearfix"></div>
                <button type="submit" class="btn btn-outline btn-info reset-filter">Reset filter</button>
                @if( user_can( 'delete', 'upload' ) )
                    @if( $check == true )
                        <button type="button" class="btn btn-w-m btn-danger btn-del-select">Xóa</button>
                    @endif
                @endif
                <button type="button" class="btn btn-block btn-outline btn-primary m-t load_more">Tải thêm file</button>
            </form>
        </div>
    </div>
</div>
<div class="col-xl-9 col-md-8 col-sm-7 full-height content-image">
    <div class="row normal-scroll-content">
        <form id="dropzone-previews"
              class="col-lg-12 @if ( $check == true ) multi @else select-image @endif @if ( $multiFile == true ) multi @endif">
            <input type="hidden" name="images" value="">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="content-previews">
                @if( !empty( $attachments ) )
                    @each( name_blade('Backend.media.image') , $attachments, 'attachment')
                @endif
            </div>
        </form>
    </div>
</div>

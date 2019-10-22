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

@push('styles')
    <!-- dropzone -->
    <link href="{{ asset('css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- jsTree -->
    <link href="{{ asset('css/plugins/jsTree/style.min.css') }}" rel="stylesheet">

    <style>
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
        }
        .file-box.active .btn-circle {
            display: inline-block;
        }
    </style>
@endpush

@push('scripts')
    <!-- Sweet alert -->
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Toastr script -->
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <!-- dropzone -->
    <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
    <!-- jsTree -->
    <script src="{{ asset('js/plugins/jsTree/jstree.min.js') }}"></script>

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
        /* Tạo file */
        Dropzone.options.dropzoneForm = {
            url: "upload",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            acceptedFiles: '.zip,.rar,audio/*,video/*,image/*,.doc,.docx,.xls,.xlsx,application/pdf',
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 16, // MB
            uploadMultiple: false,
            dictFileTooBig: 'File lớn hơn 16MB',
            init: function () {
                this.on("complete", function (file) {
                    this.removeFile(file);
                });
            },
            success: function (file, response) {

                $('#dropzone-previews').prepend(response.html);

                swal({
                    type: response.status,
                    title: response.status,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1200
                });
            },
            dictDefaultMessage: "<strong>Kéo thả files vào đây để upload lên máy chủ. </strong></br>  (Hoặc click chuột để chọn files upload.)"
        };
        /* Xóa file */
        $(document).on('click', '.btn-del-file', function () {
            let parent = $(this).closest('.file-box');
            let id     = parent.data('id');
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
                    $.ajax({
                        url: "upload/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            parent.remove();
                            swal({
                                type: response.status,
                                title: "Deleted!",
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1200
                            });
                        },
                    });
                }
            });
        });
        /* Lọc */
        $(document).on('submit', '.filter-control', function () {
            $.ajax({
                url: "upload/filter",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    _form: $(this).serializeObject()
                },
                success: function (response) {

                    $('#dropzone-previews').html(response.html);

                    toastr.success(response.message)
                },
            });
            return false;
        });
        /* Reset */
        $(document).on('click', '.reset-filter', function () {
            $('input[name="s"]').val('').trigger('change');
            $('input[name="dir"]').val('').trigger('change');
            $('input[name="sort"]').val('all').trigger('change');
            $('button[value="all"]').addClass('active').siblings().removeClass('active');
            $('#jstree1').find('a').removeClass('jstree-clicked');
        });
        /* Lọc kiểu file */
        $(document).on('click', '.file-control', function () {
            $('input[name="sort"]')
                .val(
                    $(this).val()
                ).trigger('change');
            $(this).addClass('active').siblings().removeClass('active');
        });
        /* Lọc theo ngày */
        $(document).on('click', '.dir-filter', function () {
            let parent = $(this).closest('.folder-list');
            $('input[name="dir"]')
                .val(
                    $(this).data('dir')
                ).trigger('change');

            $(this).closest('form').trigger('submit');

            return false;
        });
        /* Sắp xếp */
        $(document).on('click', '.onoffswitch-label', function () {
            $('#dropzone-previews').toggleClass('list-style');
        });
        $('#jstree1').jstree({
            'core': {
                'check_callback': true
            },
            'plugins': [ 'types', 'dnd' ],
            'types': {
                'default': {
                    'icon': 'fa fa-folder'
                },
                'html': {
                    'icon': 'fa fa-file-code-o'
                },
            }
        });
    </script>
@endpush

@php
    $attachments = \Ovic\Framework\Post::get_posts(
        [
            [ 'post_type', '=', 'attachment' ],
            [ 'status', '=', 'publish' ],
        ]
    );
@endphp

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-content">
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
                    <form action="upload" class="dropzone" id="dropzoneForm" method="POST"
                          enctype="multipart/form-data">
                        <div class="fallback">
                            <input name="file" type="file" multiple/>
                        </div>
                    </form>
                    <form method="post" class="file-manager filter-control">
                        <input type="hidden" name="dir" value=""/>
                        <input type="hidden" name="sort" value="all"/>

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
                        <h5>Folders</h5>
                        <div id="jstree1">
                            <ul class="folder-list" style="padding: 0">

                                @if( !empty( $attachments ) )
                                    @php
                                        $year           = '';
                                        $directories    = [];
                                    @endphp

                                    @foreach ( $attachments as $attachment )
                                        @php
                                            $dir_year   = explode('/',$attachment['name']);
                                            $dir_year   = array_shift($dir_year);
                                            $dir        = str_replace( $attachment['title'],'',$attachment['name'] );

                                            $directories[$dir_year][] = $dir;
                                        @endphp
                                    @endforeach

                                    @php
                                        asort( $directories );
                                    @endphp

                                    @foreach ( $directories as $year => $month )
                                        <li data-jstree='"type":"html"}'>
                                            <a href="#" data-dir="{{ $year }}" class="dir-filter">
                                                Năm {{ $year }}
                                            </a>
                                            @php
                                                $month  = array_unique( array_values( $month ) );
                                            @endphp
                                            <ul class="sub-dir">
                                                @foreach ( $month as $mon )
                                                    <li data-jstree='"type":"html"}'>
                                                        <a href="#" data-dir="{{ $mon }}"
                                                           class="dir-filter">
                                                            {{ str_replace( [$year,'/'],['',''],$mon ) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-outline btn-info reset-filter">Reset filter</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9 animated fadeInRight">
            <div class="row">
                <div id="dropzone-previews" class="col-lg-12">
                    @if( !empty( $attachments ) )
                        @each( ovic_blade('Backend.media.image') , $attachments, 'attachment')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
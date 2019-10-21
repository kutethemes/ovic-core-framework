
@section('head')
    <!-- dropzone -->
    <link href="{{ asset('css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
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
        .sub-dir {
            padding-left: 20px;
        }
        .folder-list .sub-dir li {
            display: inline-block;
            border: none;
        }
        .folder-list li {
            border: none !important;
            position: relative;
        }
        .folder-list li i.fa {
            color: #bbb;
            font-size: 10px;
        }
        .folder-list > li::after {
            content: "";
            position: absolute;
            width: 1px;
            background-color: #bbb;
            height: 100%;
            left: 4px;
            top: 0;
        }
        .folder-list > li::before {
            content: "";
            background-color: #bbb;
            width: 18px;
            height: 1px;
            position: absolute;
            left: 0;
            top: 16px;
        }
        .folder-list li .sub-dir a {
            padding: 5px;
        }
        .folder-list a.active {
            color: #1ab394;
        }
        .folder-list .sub-dir a.active {
            color: #fff;
            background-color: #1ab394;
            border-color: #1ab394
        }
        .btn btn-primary {
            border-radius: 0;
        }
    </style>
@endsection

@section('footer')
    <!-- Sweet alert -->
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Toastr script -->
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <!-- dropzone -->
    <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
    <script>
        var serializeObject = function (form) {
            var o = {};
            var a = form.serializeArray();
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
        /* Tạo file */
        Dropzone.options.dropzoneForm = {
            url: "{{ route('upload_file') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            acceptedFiles: '.zip,.rar,audio/*,video/*,image/*,.doc,.docx,.xls,.xlsx,application/pdf',
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 16, // MB
            addRemoveLinks: true,
            uploadMultiple: false,
            dictRemoveFile: 'Xóa file',
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
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (isConfirm) {
                if ( isConfirm ) {
                    $.ajax({
                        url: "{{ route('remove_file') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
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
        /* Tìm kiếm */
        $(document).on('submit', '.filter-control', function () {
            $.ajax({
                url: "{{ route('file_filter') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _form: serializeObject($(this)),
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    $('#dropzone-previews').html(response.html);

                    toastr.success(response.message)
                },
            });
            return false;
        });
        $(document).on('click', '.reset-filter', function () {
            $('input[name="s"]').val('').trigger('change');
            $('input[name="dir"]').val('').trigger('change');
            $('input[name="sort"]').val('all').trigger('change');
            $('button[value="all"]').addClass('active').siblings().removeClass('active');
            $('.folder-list').find('a').removeClass('active');
        });
        $(document).on('click', '.file-control', function () {
            $('input[name="sort"]')
                .val(
                    $(this).val()
                ).trigger('change');
            $(this).addClass('active').siblings().removeClass('active');
        });
        $(document).on('click', '.dir-filter', function () {
            let parent = $(this).closest('.folder-list');
            $('input[name="dir"]')
                .val(
                    $(this).data('dir')
                ).trigger('change');

            $(this).closest('form').trigger('submit');
            parent.find('a').removeClass('active');
            $(this).addClass('active');

            return false;
        });
        $(document).on('click', '.onoffswitch-label', function () {
            $('#dropzone-previews').toggleClass('list-style');
        });
    </script>
@endsection

@section('content')
    @php
        $attachments = \Ovic\Framework\Post::get_posts(
            [
                [ 'post_type', '=', 'attachment' ],
                [ 'status', '=', 'publish' ],
            ]
        );
    @endphp
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>File Manager</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>File Manager</strong>
                </li>
            </ol>
        </div>
    </div>
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
                        <form action="{{ route('upload_file') }}" class="dropzone" id="dropzoneForm" method="post"
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
                                        <li>
                                            <a href="#" data-dir="{{ $year }}" class="dir-filter">
                                                <i class="fa fa-circle"></i>
                                                Năm {{ $year }}
                                            </a>
                                            @php
                                                $month  = array_unique( array_values( $month ) );
                                            @endphp
                                            <ul class="sub-dir">
                                                @foreach ( $month as $mon )
                                                    <li>
                                                        <a href="#" data-dir="{{ $mon }}"
                                                           class="dir-filter btn btn-white btn-bitbucket">
                                                            {{ str_replace( [$year,'/'],['',''],$mon ) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
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
                            @each( ovic_blade('Backend.media.item') , $attachments, 'attachment')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
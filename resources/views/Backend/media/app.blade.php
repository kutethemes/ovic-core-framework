@php
    /**
     * The media file for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade('Backend.app') )

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
        $(document).on('submit', '.search-control', function () {
            $.ajax({
                url: "{{ route('file_filter') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    data: 'search',
                    s: $(this).find('input[name="s"]').val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    $('#dropzone-previews').html(response.html);

                    toastr.success(response.message)
                },
            });
            return false;
        });
    </script>
@endsection

@section('title', 'Media File')

@section('content')
    @php
        $attachments = \Ovic\Framework\Post::get_images(
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
                        <div class="file-manager">
                            <form method="post" class="input-group search-control">
                                <input type="text" name="s" class="form-control"/>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                </span>
                            </form>
                            <div class="hr-line-dashed"></div>
                            <h5>Show:</h5>
                            <div class="control-filter">
                                <a href="#" class="file-control active">All</a>
                                <a href="#" class="file-control">Documents</a>
                                <a href="#" class="file-control">Audio</a>
                                <a href="#" class="file-control">Images</a>
                                <a href="#" class="file-control">Archive</a>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <form action="{{ route('upload_file') }}" class="dropzone" id="dropzoneForm" method="post"
                                  enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="file" type="file" multiple/>
                                </div>
                            </form>
                            <div class="hr-line-dashed"></div>
                            <h5>Folders</h5>
                            <ul class="folder-list" style="padding: 0">
                                @php
                                    $directories = \Illuminate\Support\Facades\Storage::allDirectories( 'uploads' );
                                @endphp
                                @foreach ( $directories as $directory )
                                    <li>
                                        <a href="">
                                            <i class="fa fa-folder"></i>
                                            {{ str_replace( 'uploads', '', $directory ) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="clearfix"></div>
                        </div>
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
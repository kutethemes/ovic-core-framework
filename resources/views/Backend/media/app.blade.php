<?php
/**
 * The media file for our theme
 *
 * @package Ovic
 * @subpackage Framework
 *
 * @version 1.0
 */
?>
@extends( ovic_blade('Backend.app') )

@section('head')
    <link href="{{ asset('css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <style>
        .file .image {
            text-align: center;
        }
    </style>
@endsection

@section('footer')
    <!-- DROPZONE -->
    <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
    <script>
        Dropzone.options.dropzoneForm = {
            url: "{{ route('upload_file') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            acceptedFiles: '.zip,.rar,audio/*,video/*,image/*,.doc,.docx,application/pdf,application/xls',
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
                var $html = '';


                $html += '<div class="file-box" data-id="' + response.post_id + '">';
                $html += '  <div class="file">';
                $html += '      <a href="' + response.url + '" target="_blank">';
                $html += '          <span class="corner"></span>';
                $html += '          ' + response.type;
                $html += '          <div class="file-name">' + response.name;
                $html += '          <br/>';
                $html += '          <small>Added: ' + response.datetime + '</small>';
                $html += '          </div><!--.file-name-->';
                $html += '      </a>';
                $html += '  </div><!--.file-->';
                $html += '</div><!--.file-box-->';

                $('#dropzone-previews').prepend($html);
            },
            dictDefaultMessage: "<strong>Kéo thả files vào đây để upload lên máy chủ. </strong></br>  (Hoặc click chuột để chọn files upload.)"
        };
    </script>
@endsection

@section('title', 'Media File')

@section('content')
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
                            <h5>Show:</h5>
                            <a href="#" class="file-control active">All</a>
                            <a href="#" class="file-control">Documents</a>
                            <a href="#" class="file-control">Audio</a>
                            <a href="#" class="file-control">Images</a>
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
                                <li><a href=""><i class="fa fa-folder"></i> Files</a></li>
                                <li><a href=""><i class="fa fa-folder"></i> Pictures</a></li>
                                <li><a href=""><i class="fa fa-folder"></i> Web pages</a></li>
                                <li><a href=""><i class="fa fa-folder"></i> Illustrations</a></li>
                                <li><a href=""><i class="fa fa-folder"></i> Films</a></li>
                                <li><a href=""><i class="fa fa-folder"></i> Books</a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 animated fadeInRight">
                <div class="row">
                    <div id="dropzone-previews" class="col-lg-12">
						<?php
						$attachments = \Ovic\Framework\Post::where( 'post_type', 'attachment' )->get();
						$attachments = json_decode( $attachments->toJson(), true );
						foreach($attachments as $attachment):
						$meta = \Ovic\Framework\Postmeta::get_post_meta( $attachment['id'], '_attachment_meta' );
						$file_url = url( Storage::url( "app/{$meta['path']}" ) );
						if ( strstr( $meta['mimetype'], "video/" ) ) {
							$FileType = '<div class="icon"><i class="img-fluid fa fa-film"></i></div>';
						} else if ( strstr( $meta['mimetype'], "image/" ) ) {
							$FileType = '<div class="image"><img alt="image" class="img-fluid" src="' . $file_url . '"></i></div>';
						} else if ( strstr( $meta['mimetype'], "audio/" ) ) {
							$FileType = '<div class="icon"><i class="fa fa-music"></i></div>';
						} else if ( strstr( $meta['mimetype'], "xls" ) ) {
							$FileType = '<div class="icon"><i class="fa fa-bar-chart-o"></i></div>';
						} else {
							$FileType = '<div class="icon"><i class="fa fa-file"></i></div>';
						}
						?>
                        <div class="file-box">
                            <div class="file">
                                <a href="{{ $file_url }}" target="_blank">
                                    <span class="corner"></span>
									<?php echo $FileType; ?>
                                    <div class="file-name">
                                        {{ $attachment['name'] }}
                                        <br/>
                                        <small>Added: {{ $attachment['created_at'] }}</small>
                                    </div>
                                </a>

                            </div>
                        </div>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
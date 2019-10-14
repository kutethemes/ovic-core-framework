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
@endsection

@section('footer')
    <!-- DROPZONE -->
    <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
    <script>
        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // MB
            uploadMultiple: true,
            addRemoveLinks: true,
            removedfile: function (file) {
                var _ref, name = file.name;
                $.ajax({
                    type: 'POST',
                    url: 'delete.php',
                    data: "id=" + name,
                    dataType: 'html'
                });
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            },
            dictDefaultMessage: "<strong>Kéo thả files vào đây để upload lên máy chủ. </strong></br>  (Hoặc click chuột để chọn files upload.)"
        };
    </script>
@endsection

@section('title', 'Media File')

@section('breadcrumb')
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
@endsection

@section('content')
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
                            <form action="#" class="dropzone" id="dropzoneForm" method="post"
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
                    <div class="col-lg-12">
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Jan 11, 2014</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>Added: Jan 6, 2014</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>Added: Jan 7, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>Added: Jan 22, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Fab 11, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-fluid fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>Added: Fab 18, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>Added: Fab 22, 2014</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Jan 11, 2014</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>Added: Jan 6, 2014</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>Added: Jan 7, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>Added: Jan 22, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Fab 11, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-fluid fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>Added: Fab 18, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>Added: Fab 22, 2014</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Jan 11, 2014</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>Added: Jan 6, 2014</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>Added: Jan 7, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>Added: Jan 22, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-fluid" src="img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>Added: Fab 11, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-fluid fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>Added: Fab 18, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>Added: Fab 22, 2014</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


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

@section('title', 'Users')

@section('head')
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    <!-- dataTables -->
    <link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

    <style>
        #edit-user .avatar img {
            max-width: 96px;
        }
        label.float-right {
            margin-bottom: 0;
        }
        div.client-detail {
            height: 605px;
        }
        .form-group.submit {
            margin-bottom: 0;
            margin-top: 1rem;
            text-align: right;
        }
        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }
        div.modal-content {
            width: 100vw !important;
            height: 100vh !important;
        }
        div.inmodal .modal-header {
            padding: 10px;
        }
        .modal-footer {
            background-color: #fff;
        }
        .modal.show .modal-dialog {
            transform: none;
            max-width: inherit;
            margin: 0;
        }
        .file-box:hover::before {
            content: "";
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 2;
        }
        .file-box {
            position: relative;
            z-index: 3;
            cursor: pointer;
        }
    </style>
@endsection

@section('footer')
    <!-- Chosen -->
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- dataTables -->
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $('.chosen-select').chosen({
            width: "100%"
        });
        $(document).on('click', 'button.edit-field', function () {
            let group = $(this).closest('.input-group');
            let input = group.find('input');

            if ( input.attr('disabled') === undefined ) {
                input.attr('disabled', 'disabled');
            } else {
                input.removeAttr('disabled');
            }
        });
        $(document).on('click', '#dropzone-previews .file-box', function () {
            if ( $(this).find('img').length ) {
                let id        = $(this).data('id');
                let avatar_id = $('input[name="avatar"]');
                let avatar    = $('a[data-toggle="modal"]').find('img');
                let src       = $(this).find('img').attr('src');

                $(this).addClass('active').siblings().removeClass('active');

                avatar_id.val(id).trigger('change');
                avatar.attr('src', src);
            }
            return false;
        });
    </script>
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>Users Manager</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Users Manager</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <h2>Users</h2>
                        <p>
                            Descriptions Users.
                        </p>
                        <div class="input-group">
                            <input type="text" placeholder="Search users" class="input form-control">
                            <span class="input-group-append">
                                <button type="button" class="btn btn btn-primary">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </span>
                        </div>
                        <div class="clients-list">
                            <span class="float-right small text-muted">1406 Elements</span>
                            <ul class="nav nav-tabs">
                                <li>
                                    <a class="nav-link active" data-toggle="tab" href="#tab-1">
                                        All Users
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" data-toggle="tab" href="#tab-2">
                                        Administrator
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <td class="client-avatar"><img alt="image" src="img/a2.jpg"></td>
                                                    <td><a href="#contact-1" class="client-link">Anthony Jackson</a>
                                                    </td>
                                                    <td> Tellus Institute</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> gravida@rbisit.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><img alt="image" src="img/a3.jpg"></td>
                                                    <td><a href="#contact-2" class="client-link">Rooney Lindsay</a></td>
                                                    <td>Proin Limited</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> rooney@proin.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><img alt="image" src="img/a4.jpg"></td>
                                                    <td><a href="#contact-3" class="client-link">Lionel Mcmillan</a>
                                                    </td>
                                                    <td>Et Industries</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +432 955 908</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a5.jpg"></a></td>
                                                    <td><a href="#contact-4" class="client-link">Edan Randall</a></td>
                                                    <td>Integer Sem Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +422 600 213</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a6.jpg"></a></td>
                                                    <td><a href="#contact-2" class="client-link">Jasper Carson</a></td>
                                                    <td>Mone Industries</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +400 468 921</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a7.jpg"></a></td>
                                                    <td><a href="#contact-3" class="client-link">Reuben Pacheco</a></td>
                                                    <td>Magna Associates</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> pacheco@manga.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-info">Phoned</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a1.jpg"></a></td>
                                                    <td><a href="#contact-1" class="client-link">Simon Carson</a></td>
                                                    <td>Erat Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> Simon@erta.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a3.jpg"></a></td>
                                                    <td><a href="#contact-2" class="client-link">Rooney Lindsay</a></td>
                                                    <td>Proin Limited</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> rooney@proin.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a4.jpg"></a></td>
                                                    <td><a href="#contact-3" class="client-link">Lionel Mcmillan</a>
                                                    </td>
                                                    <td>Et Industries</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +432 955 908</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a5.jpg"></a></td>
                                                    <td><a href="#contact-4" class="client-link">Edan Randall</a></td>
                                                    <td>Integer Sem Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +422 600 213</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a2.jpg"></a></td>
                                                    <td><a href="#contact-1" class="client-link ">Anthony Jackson</a>
                                                    </td>
                                                    <td> Tellus Institute</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> gravida@rbisit.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-danger">Deleted</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a7.jpg"></a></td>
                                                    <td><a href="#contact-2" class="client-link">Reuben Pacheco</a></td>
                                                    <td>Magna Associates</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> pacheco@manga.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a5.jpg"></a></td>
                                                    <td><a href="#contact-3" class="client-link">Edan Randall</a></td>
                                                    <td>Integer Sem Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +422 600 213</td>
                                                    <td class="client-status"><span
                                                                class="label label-info">Phoned</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a6.jpg"></a></td>
                                                    <td><a href="#contact-4" class="client-link">Jasper Carson</a></td>
                                                    <td>Mone Industries</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +400 468 921</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a7.jpg"></a></td>
                                                    <td><a href="#contact-2" class="client-link">Reuben Pacheco</a></td>
                                                    <td>Magna Associates</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> pacheco@manga.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a1.jpg"></a></td>
                                                    <td><a href="#contact-1" class="client-link">Simon Carson</a></td>
                                                    <td>Erat Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> Simon@erta.com</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a3.jpg"></a></td>
                                                    <td><a href="#contact-3" class="client-link">Rooney Lindsay</a></td>
                                                    <td>Proin Limited</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> rooney@proin.com</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a4.jpg"></a></td>
                                                    <td><a href="#contact-4" class="client-link">Lionel Mcmillan</a>
                                                    </td>
                                                    <td>Et Industries</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +432 955 908</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a5.jpg"></a></td>
                                                    <td><a href="#contact-1" class="client-link">Edan Randall</a></td>
                                                    <td>Integer Sem Corp.</td>
                                                    <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                                    <td> +422 600 213</td>
                                                    <td class="client-status"><span
                                                                class="label label-info">Phoned</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a2.jpg"></a></td>
                                                    <td><a href="#contact-2" class="client-link">Anthony Jackson</a>
                                                    </td>
                                                    <td> Tellus Institute</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> gravida@rbisit.com</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="client-avatar"><a href=""><img alt="image"
                                                                                              src="img/a7.jpg"></a></td>
                                                    <td><a href="#contact-4" class="client-link">Reuben Pacheco</a></td>
                                                    <td>Magna Associates</td>
                                                    <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                                    <td> pacheco@manga.com</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                    <div class="full-height-scroll">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tellus Institute</a>
                                                    </td>
                                                    <td>Rexton</td>
                                                    <td><i class="fa fa-flag"></i> Angola</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Velit Industries</a>
                                                    </td>
                                                    <td>Maglie</td>
                                                    <td><i class="fa fa-flag"></i> Luxembourg</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link">Art Limited</a></td>
                                                    <td>Sooke</td>
                                                    <td><i class="fa fa-flag"></i> Philippines</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tempor Arcu Corp.</a>
                                                    </td>
                                                    <td>Eisden</td>
                                                    <td><i class="fa fa-flag"></i> Korea, North</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Penatibus
                                                            Consulting</a></td>
                                                    <td>Tribogna</td>
                                                    <td><i class="fa fa-flag"></i> Montserrat</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link"> Ultrices
                                                            Incorporated</a></td>
                                                    <td>Basingstoke</td>
                                                    <td><i class="fa fa-flag"></i> Tunisia</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Et Arcu Inc.</a></td>
                                                    <td>Sioux City</td>
                                                    <td><i class="fa fa-flag"></i> Burundi</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tellus Institute</a>
                                                    </td>
                                                    <td>Rexton</td>
                                                    <td><i class="fa fa-flag"></i> Angola</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Velit Industries</a>
                                                    </td>
                                                    <td>Maglie</td>
                                                    <td><i class="fa fa-flag"></i> Luxembourg</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link">Art Limited</a></td>
                                                    <td>Sooke</td>
                                                    <td><i class="fa fa-flag"></i> Philippines</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tempor Arcu Corp.</a>
                                                    </td>
                                                    <td>Eisden</td>
                                                    <td><i class="fa fa-flag"></i> Korea, North</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Penatibus
                                                            Consulting</a></td>
                                                    <td>Tribogna</td>
                                                    <td><i class="fa fa-flag"></i> Montserrat</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link"> Ultrices
                                                            Incorporated</a></td>
                                                    <td>Basingstoke</td>
                                                    <td><i class="fa fa-flag"></i> Tunisia</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Et Arcu Inc.</a></td>
                                                    <td>Sioux City</td>
                                                    <td><i class="fa fa-flag"></i> Burundi</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tellus Institute</a>
                                                    </td>
                                                    <td>Rexton</td>
                                                    <td><i class="fa fa-flag"></i> Angola</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Velit Industries</a>
                                                    </td>
                                                    <td>Maglie</td>
                                                    <td><i class="fa fa-flag"></i> Luxembourg</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link">Art Limited</a></td>
                                                    <td>Sooke</td>
                                                    <td><i class="fa fa-flag"></i> Philippines</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-1" class="client-link">Tempor Arcu Corp.</a>
                                                    </td>
                                                    <td>Eisden</td>
                                                    <td><i class="fa fa-flag"></i> Korea, North</td>
                                                    <td class="client-status"><span
                                                                class="label label-warning">Waiting</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Penatibus
                                                            Consulting</a></td>
                                                    <td>Tribogna</td>
                                                    <td><i class="fa fa-flag"></i> Montserrat</td>
                                                    <td class="client-status"></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-3" class="client-link"> Ultrices
                                                            Incorporated</a></td>
                                                    <td>Basingstoke</td>
                                                    <td><i class="fa fa-flag"></i> Tunisia</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#company-2" class="client-link">Et Arcu Inc.</a></td>
                                                    <td>Sioux City</td>
                                                    <td><i class="fa fa-flag"></i> Burundi</td>
                                                    <td class="client-status"><span
                                                                class="label label-primary">Active</span></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox selected">
                    <div class="ibox-content">
                        <form action="#" id="edit-user" method="post">
                            <div class="row m-b-lg">
                                <div class="col-lg-12 text-center">
                                    <a href="#" data-toggle="modal" data-target="#modal-media" class="avatar">
                                        <img alt="avatar" class="rounded-circle" src="img/a8.jpg">
                                    </a>
                                    <input type="hidden" name="avatar" value="">
                                </div>
                            </div>
                            <div class="client-detail">
                                <div class="full-height-scroll">

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">
                                            Name
                                        </label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control" placeholder="Name"
                                                       required="" aria-required="true">
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

                                    <div class="form-group  row">
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

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">
                                            Mật khẩu
                                        </label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="password" class="form-control"
                                                       placeholder="Mật khẩu >= 6 ký tự"
                                                       name="password" aria-required="true" aria-invalid="false"
                                                       disabled minlength="6">
                                                <span class="input-group-append">
                                                    <button class="btn btn-info edit-field" type="button">
                                                        <i class="fa fa-paste"></i> Edit
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">
                                            Đơn vị
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="donvi_id" class="form-control chosen-select"
                                                    data-placeholder="Chọn đơn vị">
                                                <option value="">Select</option>
                                                <option value="1">Kích hoạt</option>
                                                <option value="2">Kích hoạt ẩn</option>
                                                <option value="0">Không kích hoạt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">
                                            Nhóm quyền
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="role_ids" class="form-control chosen-select"
                                                    multiple="multiple">
                                                <option value="1">Kích hoạt</option>
                                                <option value="2">Kích hoạt ẩn</option>
                                                <option value="0">Không kích hoạt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">
                                            Phạm vi quản lý
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="donvi_ids" class="form-control chosen-select"
                                                    multiple="multiple">
                                                <option value="1">Kích hoạt</option>
                                                <option value="2">Kích hoạt ẩn</option>
                                                <option value="0">Không kích hoạt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                </div>
                            </div>

                            <div class="form-group submit row">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-save"></i>
                                        Save change
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal fade" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Media</h4>
                </div>
                <div class="modal-body">
                    @include( ovic_blade('Backend.media.content') )
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


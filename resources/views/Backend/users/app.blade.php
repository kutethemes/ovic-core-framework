@php
    /**
     * The main user for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade('Backend.app') )

@section('title', 'Users Manager')

@push('styles')
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
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
        .btn-danger {
            float: left;
        }
        #users-table thead th {
            pointer-events: none;
        }
        .head-group .button-group {
            float: right;
        }
        .client-name,
        .client-email {
            width: 210px;
        }
        .client-options {
            width: 110px;
            text-align: center;
        }
        .client-options > * {
            margin: 0 5px;
        }
    </style>
@endpush

@push('scripts')
    <!-- dataTables -->
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        var table = $('#users-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: "users/list",
                dataType: "json",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                error: function () {
                    swal({
                        type: 'error',
                        title: "Error!",
                        text: "Không tải được dữ liệu.",
                        showConfirmButton: true
                    });
                },
            },
            autoWidth: true,
            columns: [
                {
                    className: "client-avatar",
                    data: "avatar"
                },
                {
                    className: "client-name",
                    data: "name"
                },
                {
                    className: "client-donvi",
                    data: "donvi_id"
                },
                {
                    className: "client-email",
                    data: "email"
                },
                {
                    className: "client-options",
                    data: "status"
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Vietnamese.json"
            }
        });
        $(document).on('click', '.btn-group button', function () {
            let button = $(this);

            $('th.client-options').trigger('click');

            button.toggleClass('btn-primary btn-white');
        });
        $(document).on('click', '#dropzone-previews .file-box', function () {
            if ( $(this).find('img').length ) {
                $(this).addClass('active').siblings().removeClass('active');
            }
            return false;
        });
    </script>
@endpush

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
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="head-group col-sm-12">
                            <button class="btn btn-primary add-new" type="submit">
                                <i class="fa fa-plus"></i>
                                Add new
                            </button>
                            <div class="button-group">
                                <span class="font-bold">Xắp xếp theo:</span>
                                <div class="btn-group">
                                    <button class="btn btn-white" type="button">Kích hoạt</button>
                                    <button class="btn btn-white" type="button">Kích hoạt ẩn</button>
                                    <button class="btn btn-white" type="button">Không kích hoạt</button>
                                </div>
                            </div>
                        </div>
                        <div class="clients-list">
                            <table id="users-table" class="table table-striped table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Tên hiển thị</th>
                                    <th>Đơn vị</th>
                                    <th>Email</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox selected">
                    <div class="ibox-content">
                        @include( ovic_blade('Backend.users.edit') )
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal fade" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thư viện</h4>
                </div>
                <div class="modal-body">
                    @include( ovic_blade('Backend.media.content') )
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Chọn ảnh</button>
                </div>
            </div>
        </div>
    </div>
@endsection


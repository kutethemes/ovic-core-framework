@php
    /**
     * The email for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
    $title = 'HỘP THƯ ĐẾN';
@endphp

@extends( name_blade('Backend.app') )

@switch( $mailbox )
    @case( 'send' )
    @php $title = 'GỬI THƯ' @endphp
    @break
    @case( 'outbox' )
    @php $title = 'HỘP THƯ ĐI' @endphp
    @break
    @case( 'draft' )
    @php $title = 'HỘP THƯ NHÁP' @endphp
    @break
    @case( 'trash' )
    @php $title = 'HỘP THƯ RÁC' @endphp
    @break
@endswitch

@section( 'title', $title )

@push( 'styles' )
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <style>
        .table-mail .check-mail {
            padding-left: 20px !important;
        }

        table.table-mail tr td {
            padding: 12px !important;
        }

        .ibox .mailbox-content {
            padding: 20px;
        }

        .file-manager .folder-list li a {
            padding: 10px 0;
        }

        .mail-search .btn {
            border-radius: 0;
            height: 100%;
        }

        #table-email thead {
            display: none;
        }

        .table-mail tbody > tr {
            cursor: pointer;
        }

        .form-control[name="send_type"] {
            margin-bottom: 5px;
        }

        .note-toolbar-wrapper {
            height: auto !important;
        }

        .dataTables_wrapper {
            position: relative;
        }

        .dataTables_processing.card {
            position: absolute;
            border: none;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .sk-spinner.sk-spinner-wave {
            display: block;
            position: absolute;
            top: calc(50% - 20px);
            left: 0;
            right: 0;
        }

        .form-group.files .chosen-choices .search-choice:first-child {
            margin: 5px 0 0 0;
        }

        .form-group.files .chosen-choices .search-choice {
            margin: 5px 0 3px 0;
            width: 100%;
        }

        .form-group.files .chosen-choices .search-field {
            display: none;
        }

        .form-group.files .chosen-choices,
        .form-group.files .ovic-image-add {
            border-radius: 0;
        }

        .form-group.files .chosen-choices {
            border: none;
        }

        .form-group.files .chosen-drop {
            display: none;
        }

        .form-control[name="send_type"] {
            padding-left: 7px;
        }

        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #aaa !important;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: #aaa;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            color: #aaa;
        }

        .mail-attachment .btn-circle,
        .mail-attachment .btn-del-file {
            display: none;
        }

        .folder-list > li.active a,
        .folder-list > li.active i {
            color: #1ab394;
        }

        .folder-list > li.active {
            border-bottom-color: #1ab394;
        }
    </style>
@endpush

@section( 'content' )

    <div class="col-lg-3 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content mailbox-content p-20">
                <div class="file-manager">
                    <a class="btn btn-block btn-primary compose-mail" href="email?mailbox=send">Gửi mail</a>
                    <div class="space-25"></div>
                    <h5>DANH MỤC</h5>
                    <ul class="folder-list m-b-md" style="padding: 0">
                        <li class="{{ $mailbox == 'inbox' ? 'active' : '' }}">
                            <a href="{{ asset( 'email?mailbox=inbox' ) }}">
                                <i class="fa fa-inbox "></i> Hộp thư đến
                                <span
                                    class="label label-warning float-right label-inbox">{{ $counting['inbox'] }}</span>
                            </a>
                        </li>
                        <li class="{{ $mailbox == 'outbox' ? 'active' : '' }}">
                            <a href="{{ asset( 'email?mailbox=outbox' ) }}">
                                <i class="fa fa-envelope-o"></i> Hộp thư đi
                                <span
                                    class="label label-warning float-right label-outbox">{{ $counting['outbox'] }}</span>
                            </a>
                        </li>
                        <li class="{{ $mailbox == 'draft' ? 'active' : '' }}">
                            <a href="{{ asset( 'email?mailbox=draft' ) }}">
                                <i class="fa fa-file-text-o"></i> Hộp thư nháp
                                <span class="label label-danger float-right label-draft">{{ $counting['draft'] }}</span>
                            </a>
                        </li>
                        <li class="{{ $mailbox == 'trash' ? 'active' : '' }}">
                            <a href="{{ asset( 'email?mailbox=trash' ) }}">
                                <i class="fa fa-trash-o"></i> Hộp thư rác
                                <span class="label label-danger float-right label-trash">{{ $counting['trash'] }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 full-height">

        @if( $mailbox == 'send' || $mailbox == 'edit' )
            @include( name_blade( 'Backend.email.send' ) )
        @elseif( $mailbox == 'show' )
            @include( name_blade( 'Backend.email.detail' ) )
        @else
            @include( name_blade( 'Backend.email.list' ) )
        @endif

    </div>

@endsection

@push( 'scripts' )
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        toastr.options = {
            "preventDuplicates": true,
        };

        function update_count( data ) {
            let list = $( '.folder-list' );
            $.each( data, function ( index, value ) {
                list.find( '.label-' + index ).text( value );
            } )
        }

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
        var csrf       = $( 'meta[name="csrf-token"]' ).attr( 'content' ),
            tableEmail = $( '#table-email' ).DataTable( {
                processing: true,
                serverSide: true,
                dom: 'rt<"footer-table"p><"clear">',
                columns: [
                    {
                        className: "check-mail",
                        data: 'id',
                        sortable: false,
                        render: function ( data, type, row, meta ) {
                            return '<input id="select-' + data + '" class="select-items i-checks" type="checkbox" name="ids[]" value="' + data + '">';
                        }
                    },
                    {
                        className: "mail-ontact",
                        data: 'nguoigui',
                        sortable: false
                    },
                    {
                        className: "mail-subject",
                        data: 'tieude',
                        sortable: false
                    },
                    {
                        data: 'files',
                        sortable: false,
                        render: function ( data, type, row, meta ) {
                            if ( $.isArray( data ) && data.length > 0 ) {
                                return '<i class="fa fa-paperclip"></i>';
                            }
                            return '';
                        }
                    },
                    {
                        className: "text-right mail-date",
                        data: 'created_at',
                        sortable: false
                    },
                ],
                ajax: {
                    url: "{{ asset('email')  }}/create",
                    dataType: "json",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    data: function ( data ) {
                        data.mailbox = "{{ $mailbox }}";
                    },
                    complete: function ( xhr, status ) {
                        if ( status === 'success' ) {
                            $( '.mail-box-header .countTotal' ).text( xhr.responseJSON.recordsTotal );
                            $( '.i-checks' ).iCheck( {
                                checkboxClass: 'icheckbox_square-green',
                                radioClass: 'iradio_square-green',
                            } );
                        }
                    },
                    error: function () {
                        swal( {
                            type: 'error',
                            title: "Error!",
                            text: "Không tải được dữ liệu.",
                            showConfirmButton: true
                        } );
                    },
                },
                createdRow: function ( row, data, dataIndex ) {
                    // Set the data-status attribute, and add a class
                    $( row ).attr( 'data-id', data.id );
                    if ( data.receive[0].status !== undefined && parseInt( data.receive[0].status ) === 1 ) {
                        $( row ).addClass( 'read' );
                    } else {
                        $( row ).addClass( 'unread' );
                    }
                },
                language: {
                    url: "{{ asset('datatable_language/vi.json') }}"
                },
            } );

        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Không tìm thấy kết quả!",
            disable_search_threshold: 8,
            allow_single_deselect: true
        } );

        $( document ).on( 'click', '#modal-media .save-modal', function () {
            let ids   = [],
                html  = '',
                form  = $( '#form-send' ),
                media = $( '#modal-media' );

            media.find( '.content-previews .file-box.active' ).each( function () {
                let id   = $( this ).data( 'id' ),
                    name = $( this ).find( '.name' ).text();

                ids.push( id );
                html += '<option value="' + id + '">' + name + '</option>';
            } );

            form.find( '.form-group select[name="files"]' ).empty().append( html ).val( ids ).trigger( 'chosen:updated' );

            return false;
        } );

        $( document ).on( 'click', '.table-refresh', function () {
            tableEmail.ajax.reload();
        } );

        $( document ).on( 'submit', 'form.mail-search', function () {
            let form = $( this ),
                data = form.serializeObject();

            tableEmail.search( data.search ).draw();

            return false;
        } );

        $( document ).on( 'change', '.form-control[name="send_type"]', function () {
            let value  = $( this ).val(),
                parent = $( this ).parent(),
                input  = parent.find( '[name="nguoinhan"]' ),
                text   = parent.find( '.form-text' );

            if ( parseInt( value ) === 0 ) {
                input.show();
                text.show();
            } else {
                input.hide();
                text.hide();
            }
        } );

        $( document ).on( 'click', '.ibox-content .discard-email', function () {
            let button  = $( this ),
                content = button.closest( '.ibox-content' ),
                form    = content.find( 'form#form-send' );

            form.trigger( 'reset' );
            form.find( '.form-text' ).show();
            form.find( '[name="nguoinhan"]' ).show();
            $( '#summernote' ).summernote( 'reset' );

            return false;
        } );

        $( document ).on( 'click', '.ibox-content .send-email', function () {
            let button  = $( this ),
                content = button.closest( '.ibox-content' ),
                form    = content.find( 'form#form-send' ),
                data    = form.serializeObject(),
                type    = "POST",
                ajaxurl = "{{ asset('email')  }}";

            data.status = 1;
            if ( button.hasClass( 'draft-email' ) ) {
                data.status = 0;
            }
            data.noidung = $( '#summernote' ).summernote( 'code' );

            if ( "{{ $mailbox }}" === 'edit' ) {
                type    = "PUT";
                ajaxurl = "{{ asset('email')  }}/" + data.id;
            }

            $.ajax( {
                url: ajaxurl,
                type: type,
                dataType: 'json',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                success: function ( response ) {

                    if ( response.status === 200 ) {

                        update_count( response.data );
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

            return false;
        } );

        $( document ).on( 'click', '#table-email tbody > tr', function () {
            let row  = $( this ),
                data = tableEmail.row( this ).data(),
                url  = "{{ asset( 'email' ) }}/" + data.id;

            if ( "{{ $mailbox }}" === 'draft' ) {
                url += "/edit";
            }

            if ( row.hasClass( 'unread' ) ) {

                data.read = true;

                $.ajax( {
                    url: "{{ asset('email')  }}/" + data.id,
                    type: 'PUT',
                    dataType: 'json',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    success: function ( response ) {

                        if ( response.status === 200 ) {

                            window.location.href = url;

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
            } else {
                window.location.href = url;
            }

            return false;
        } );

        $( document ).on( 'click', '.table-delete', function () {
            let ids     = [],
                data    = {},
                type    = 'PUT',
                data_id = $( this ).data( 'id' ),
                items   = $( '#table-email .select-items' ),
                title   = "Xóa các thư đã chọn?",
                message = "Khi đồng ý xóa dữ liệu sẽ vào thùng rác!";

            if ( data_id == undefined && $( '#table-email .icheckbox_square-green.checked' ).length <= 0 ) {
                swal( {
                    type: 'warning',
                    title: 'Bạn chưa chọn thư.',
                    showConfirmButton: true,
                } );

                return false;
            }

            data.delete = true;

            if ( $( this ).hasClass( 'restore' ) ) {
                data.restore = true;
                title        = "Khôi phục các thư đã chọn?";
                message      = "";
            } else {
                if ( "{{ $mailbox }}" == 'trash' ) {
                    type    = 'DELETE';
                    message = "Khi đồng ý xóa dữ liệu sẽ không thể khôi phục được!";
                }
            }

            if ( data_id != undefined ) {
                ids = data_id;
            } else {
                items.each( function () {
                    if ( $( this ).is( ':checked' ) ) {
                        ids.push(
                            $( this ).val()
                        );
                    }
                } );
            }

            swal( {
                title: title,
                text: message,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Đồng ý!",
                closeOnConfirm: false
            }, function ( isConfirm ) {
                if ( isConfirm ) {

                    $.ajax( {
                        url: "{{ asset('email')  }}/" + ids,
                        type: type,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        },
                        data: data,
                        success: function ( response ) {

                            if ( response.status === 'success' ) {

                                if ( data_id != undefined ) {
                                    window.location.href = "{{ asset('email')  }}";
                                } else {
                                    update_count( response.data );
                                    tableEmail.ajax.reload( null, false );
                                }

                            }

                            if ( data_id == undefined ) {
                                swal( {
                                    type: response.status,
                                    title: response.title,
                                    text: response.message,
                                    showConfirmButton: true,
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

                }
            } );

            return false;
        } );

        $( document ).ready( function () {
            $( '#summernote' ).summernote( {
                tabsize: 2,
                height: 250,
                toolbar: [
                    [ 'style', [ 'style' ] ],
                    [ 'font', [ 'bold', 'underline', 'clear' ] ],
                    [ 'fontname', [ 'fontname' ] ],
                    [ 'color', [ 'color' ] ],
                    [ 'para', [ 'ul', 'ol', 'paragraph' ] ],
                    [ 'table', [ 'table' ] ],
                    [ 'view', [ 'fullscreen' ] ]
                ],
            } );
        } );
    </script>
@endpush

@php
    /**
     * The Config for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', 'CÀI ĐẶT HỆ THỐNG' )

@push( 'styles' )
    {{-- Toastr style --}}
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    {{-- Sweet Alert --}}
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.min.css') }}" rel="stylesheet">
    {{-- Ladda style --}}
    <link href="{{ asset('css/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">
    {{-- Chosen --}}
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endpush

@push( 'scripts' )
    {{-- Sweet Alert --}}
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Toastr script --}}
    <script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Ladda --}}
    <script src="{{ asset('js/plugins/ladda/spin.min.js') }}"></script>
    <script src="{{ asset('js/plugins/ladda/ladda.min.js') }}"></script>
    <script src="{{ asset('js/plugins/ladda/ladda.jquery.min.js') }}"></script>
    {{-- Chosen --}}
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{-- script config --}}
    <script>
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Oops, nothing found!",
            disable_search_threshold: 5
        } );
        $( document ).on( 'click', 'button.action-config', function () {
            let button = $( this ),
                action = button.data( 'action' ),
                loading = button.ladda();

            loading.ladda( 'start' );

            $.ajax( {
                url: action,
                dataType: "json",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                },
                success: function ( response ) {
                    swal( {
                        type: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: true,
                    } );
                    loading.ladda( 'stop' );
                },
                error: function () {
                    swal( {
                        type: 'error',
                        title: 'Error!',
                        text: 'Cập nhật không thành công.',
                        showConfirmButton: true,
                    } );
                    loading.ladda( 'stop' );
                }
            } );
        } );
    </script>
@endpush

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox full-height-scroll">

            <div class="ibox-content full-height">
                <form id="config" action="" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Site Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="title" value="{{ config('app.name') }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Timezone
                        </label>
                        <div class="col-sm-10">
                            <select class="form-control chosen-select" name="timezone">
                                {{ ovic_timezone_choice( config('app.timezone') ) }}
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Thư viện
                            <br>
                            <small class="text-navy">Cập nhật thư viện assets.</small>
                        </label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary action-config" data-action="update-assets" type="button">
                                <i class="fa fa-download"></i>
                                Update thư viện
                            </button>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Cache
                            <br>
                            <small class="text-navy">Xóa tất cả cache.</small>
                        </label>
                        <div class="col-sm-10">
                            <button class="btn btn-danger action-config" data-action="clear-cache" type="button">
                                <i class="fa fa-warning"></i>
                                Xóa cache
                            </button>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group submit row">
                        <div class="col-sm-12">
                            <button class="btn btn-primary action-config" data-action="save-change" type="button">
                                <i class="fa fa-save"></i>
                                Save change
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection

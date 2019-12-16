@php
    /**
     * The importer for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', 'IMPORT DỮ LIỆU' )

@push('styles')
    <style>
        #page-wrapper .ibox-content {
            background: transparent;
            border: none;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
@endpush

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">

                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a class="nav-link active" data-toggle="tab" href="#tab-1">
                                <i class="fa fa-download"></i>
                                Export người dùng
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" data-toggle="tab" href="#tab-2">
                                <i class="fa fa-upload"></i>
                                Import người dùng
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-1" class="tab-pane active">
                            <div class="panel-body">

                                <form action="" id="importer-data">
                                    <input type="hidden" name="type" value="export">
                                    <input type="hidden" name="target" value="user">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">
                                            Export theo đơn vị
                                            <br>
                                            <small class="text-navy">Những người dùng trong đơn vị được chọn quản lý.</small>
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="donvi" class="custom-select">
                                                <option value="">Tất cả</option>
                                                {!!
                                                    _menu_tree( $donvi, [
                                                       'title'  =>  'tendonvi',
                                                       'type'   =>  'dropdown',
                                                    ]);
                                                !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group submit row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">
                                                Export người dùng
                                            </button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div role="tabpanel" id="tab-2" class="tab-pane">
                            <div class="panel-body">

                                <form action="" id="importer-data">
                                    <input type="hidden" name="type" value="import">
                                    <input type="hidden" name="target" value="user">

                                    <div class="form-group submit row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">
                                                Import người dùng
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
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
        $( document ).on( 'submit', '#importer-data', function () {
            let form = $( this ),
                data = form.serializeObject(),
                url  = 'importer/create?' + $.param( data )

            // console.log(url);
            window.location = url;

            return false;
        } );
    </script>
@endpush

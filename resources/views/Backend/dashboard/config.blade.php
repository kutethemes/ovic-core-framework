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

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox full-height-scroll">

            <div class="ibox-content">
                <form id="config" action="" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Normal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection

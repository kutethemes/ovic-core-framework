@php
    /**
     * The main ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade( 'Components.table' ) )

@section( 'title', 'PHÂN QUYỀN CHỨC NĂNG' )

@section( 'content-table' )

    <div class="col-sm-8 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-content">
                @include( ovic_blade('Backend.ucases.list') )
            </div>
        </div>
    </div>
    <div class="col-sm-4 full-height">
        <div class="ibox selected full-height-scroll">
            <div class="ibox-content">
                @include( ovic_blade('Backend.ucases.edit') )
            </div>
        </div>
    </div>

@endsection

@push( 'after-content' )

    @include( ovic_blade('Fields.icon.modal') )

@endpush

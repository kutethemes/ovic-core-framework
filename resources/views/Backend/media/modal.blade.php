@php
    /**
     * The template modal for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles' )
    <style>
        div.modal-content {
            width: 100vw !important;
            height: calc(100vh - 50px) !important;
            border: none;
            border-radius: 0;
            box-shadow: none;
            display: block;
        }
        div.modal-footer {
            box-shadow: none;
            border-radius: 0;
            border-top: 1px solid #e7eaec;
        }
        div.inmodal .modal-header {
            padding: 10px;
            border-bottom: 1px solid #e7eaec;
        }
        .modal-footer {
            background-color: #fff;
        }
        .modal.show .modal-dialog {
            transform: none;
            max-width: inherit;
            margin: 0;
        }
        .modal-open .modal {
            padding: 0 !important;
        }
    </style>
@endpush

<div class="modal inmodal" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thư viện</h4>
            </div>
            <div class="modal-body row full-height-content">
                @include( ovic_blade('Backend.media.data'), [ 'multi'=> false ] )
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Chọn ảnh</button>
            </div>
        </div>
    </div>
</div>
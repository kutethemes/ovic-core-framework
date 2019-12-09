@php
    /**
     * The email for our theme
     *
     * @var $email
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="ibox normal-scroll-content m-0">
    <div class="ibox-content p-0">
        <div class="mail-box-header">
            <div class="float-right tooltip-demo">
                <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="Reply">
                    <i class="fa fa-reply"></i> Reply
                </a>
                <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top"
                   title="Print email">
                    <i class="fa fa-print"></i>
                </a>
                <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="Move to trash">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
            <h2>
                View Message
            </h2>
            <div class="mail-tools tooltip-demo m-t-md">
                <h3>
                    <span class="font-normal">Tiêu đề: </span>{{ $email->tieude }}
                </h3>
                <h5>
                    <span class="float-right font-normal">{{ $email->created_at }}</span>
                    <span class="font-normal">Người gửi: </span>{{ $email->nguoigui }}
                </h5>
            </div>
        </div>
        <div class="mail-box">

            <div class="mail-body">
                {!! $email->noidung !!}
            </div>
            <div class="mail-attachment">
                <p>
                    <span><i class="fa fa-paperclip"></i> 0 attachments - </span>
                </p>

                <div class="attachment">

                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="mail-body text-right tooltip-demo">
                <a class="btn btn-sm btn-white" href="#">
                    <i class="fa fa-reply"></i>
                    Reply
                </a>
                <a class="btn btn-sm btn-white" href="#">
                    <i class="fa fa-arrow-right"></i>
                    Forward
                </a>
                <button title="" data-placement="top" data-toggle="tooltip" type="button"
                        data-original-title="Print" class="btn btn-sm btn-white">
                    <i class="fa fa-print"></i>
                    Print
                </button>
                <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash"
                        class="btn btn-sm btn-white">
                    <i class="fa fa-trash-o"></i>
                    Remove
                </button>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
</div>
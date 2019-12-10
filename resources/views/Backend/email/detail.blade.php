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
                <a href="{{ $reply }}" class="btn btn-white btn-sm" data-toggle="tooltip"
                   data-placement="top" title="Trả lời thư">
                    <i class="fa fa-reply"></i> Trả lời
                </a>
                <a href="#" class="btn btn-white btn-sm disabled" data-toggle="tooltip" data-placement="top"
                   title="Print email">
                    <i class="fa fa-print"></i>
                </a>
                <a href="#" class="btn btn-white btn-sm table-delete" data-toggle="tooltip"
                   data-id="{{ $email['id'] }}"
                   data-placement="top" title="Chuyển thư vào thùng rác">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
            <h2>
                Chi tiết thư
            </h2>
            <div class="mail-tools tooltip-demo m-t-md">
                <h3>
                    <span class="font-normal">Tiêu đề: </span>{{ $email['tieude'] }}
                </h3>
                <h5>
                    <span class="float-right font-normal">{{ $email['created_at'] }}</span>
                    <span class="font-normal">Người gửi: </span>{{ $email['nguoigui'] }}
                </h5>
                <h5>
                    <p class="font-normal" style="margin-bottom: 5px;">Người nhận: </p>
                    <ul>
                        @foreach( $email['receive'] as $receive )
                            <li>{{ $receive['nguoinhan'] }}</li>
                        @endforeach
                    </ul>
                </h5>
            </div>
        </div>
        <div class="mail-box">

            <div class="mail-body">
                {!! $email['noidung'] !!}
            </div>

            @if( !empty( $email['files'] ) )
                <div class="mail-attachment">
                    <p>
                        <span><i class="fa fa-paperclip"></i> {{ count( $email['files'] ) }} attachments </span>
                    </p>

                    <div class="attachment">
                        @foreach( $email['files'] as $file )
                            @include( name_blade('Backend.media.image'), ['attachment'=>$file] )
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                </div>
            @endif

            <div class="mail-body text-right tooltip-demo">
                <a class="btn btn-sm btn-white" href="{{ $reply }}">
                    <i class="fa fa-reply"></i>
                    Trả lời
                </a>
                <a class="btn btn-sm btn-white" href="{{ $forward }}">
                    <i class="fa fa-arrow-right"></i>
                    Chuyển tiếp
                </a>
                <button title="" data-placement="top" data-toggle="tooltip" type="button"
                        data-original-title="Print" class="btn btn-sm btn-white" disabled>
                    <i class="fa fa-print"></i>
                    Print
                </button>
                <button title="" data-placement="top" data-toggle="tooltip"
                        data-original-title="Chuyển thư vào thùng rác"
                        data-id="{{ $email['id'] }}"
                        class="btn btn-sm btn-white table-delete">
                    <i class="fa fa-trash-o"></i>
                    Xóa
                </button>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
</div>

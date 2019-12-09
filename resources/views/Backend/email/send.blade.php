@php
    /**
     * The email for our theme
     *
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
                <a href="#" class="btn btn-white btn-sm send-email draft-email" data-toggle="tooltip"
                   data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
                <a href="#" class="btn btn-danger btn-sm discard-email" data-toggle="tooltip"
                   data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
            </div>
            <h2>
                Soạn thư
            </h2>
        </div>
        <div class="mail-box">

            <div class="mail-body">

                <form id="form-send" method="get">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tiêu đề:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tieude" placeholder="Tiêu đề">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Gửi đến:</label>
                        <div class="col-sm-10">
                            <select name="send_type" class="form-control">
                                <option value="0">Trực tiếp đến cá nhân</option>
                                @if( !empty( config('danhmuc.khoi') ) )
                                    @foreach ( config('danhmuc.khoi') as $key => $value )
                                        <option value="{{ $key }}">Khối {{ $value }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="text" class="form-control" name="nguoinhan" placeholder="Email nhận">
                            <span class="form-text m-b-none">
                                Phân cách email bởi dấu phẩy nếu muốn gửi nhiều email.
                            </span>
                        </div>
                    </div>
                </form>

            </div>

            <div class="mail-text h-200">

                <label for="summernote"></label><textarea id="summernote"></textarea>
                <div class="clearfix"></div>

            </div>
            <div class="mail-body text-right tooltip-demo">
                <a href="#" class="btn btn-sm btn-primary send-email" data-toggle="tooltip"
                   data-placement="top" title="Gửi thư">
                    <i class="fa fa-reply"></i> Send
                </a>
                <a href="#" class="btn btn-white btn-sm discard-email" data-toggle="tooltip"
                   data-placement="top" title="Discard email">
                    <i class="fa fa-times"></i> Discard
                </a>
                <a href="#" class="btn btn-white btn-sm send-email draft-email" data-toggle="tooltip"
                   data-placement="top" title="Move to draft folder">
                    <i class="fa fa-pencil"></i> Draft
                </a>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
</div>
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
    <div class="ibox-content full-height p-0">
        <div class="mail-box-header">
            <h2>
                Tổng số (<span class="countTotal">0</span>)
            </h2>
            <div class="mail-tools tooltip-demo m-t-md">
                <form method="get" action="" class="float-right mail-search">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="search"
                               placeholder="Search email">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <button class="btn btn-white table-refresh btn-sm" data-toggle="tooltip" data-placement="left"
                        title="Refresh inbox">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
                @if( $mailbox == 'trash' )
                    <button class="btn btn-white table-delete restore btn-sm" data-toggle="tooltip" data-placement="top"
                            title="Khôi phục thư">
                        <i class="fa fa-undo"></i> Restore
                    </button>
                @endif
                <button class="btn btn-white table-delete btn-sm" data-toggle="tooltip" data-placement="top"
                        title="@if( $mailbox == 'trash' ) Xóa vĩnh viễn @else Chuyển đến thư mục rác @endif">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
        </div>
        <div class="mail-box">
            <table id="table-email" class="table table-hover table-mail" style="width: 100%"></table>
        </div>
    </div>
</div>

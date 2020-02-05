@php
    /**
     * The edit ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="ibox-title">
    <a href="#" class="btn btn-warning btn-xs save-change">Lưu thay đổi</a>
    <h5>Danh sách menu <small>Menu Left / Menu Top</small></h5>
    <div class="ibox-tools">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="fa fa-wrench"></i>
            Config
        </a>
        <ul id="nestable-menu" class="dropdown-menu dropdown-menu-right dropdown-order" x-placement="bottom-start">
            <li><a href="#" data-action="expand-all" class="dropdown-item">Expand All</a></li>
            <li><a href="#" data-action="collapse-all" class="dropdown-item">Collapse All</a></li>
        </ul>
    </div>
</div>
<div class="ibox-content ibox-list">
    <div class="sk-spinner sk-spinner-wave">
        <div class="sk-rect1"></div>
        <div class="sk-rect2"></div>
        <div class="sk-rect3"></div>
        <div class="sk-rect4"></div>
        <div class="sk-rect5"></div>
    </div>
    @if( !empty( $menus ) )
        <form action="" id="list-posts">
            <input type="hidden" name="id" value="0">
            <div class="row">
                @foreach( $menus as $key => $menu )
                    <div class="col-sm-6{{ $key == 'menu-left' ? ' b-r' : '' }}">
                        <div id="{{ $key }}" class="dd">
                            <ol class="dd-list dd-nodrag">
                                @if( !empty( $menu ) )
                                    @foreach ( $menu as $parent )
                                        <li class="dd-item{{ !empty( $parent['children'] ) ? ' has-children' : '' }}"
                                            data-slug="{{ $parent['slug'] }}">

                                            @include( name_blade('Backend.permission.item'), ['data' => $parent] )

                                            @if( !empty( $parent['children'] ) )

                                                <ol class="dd-list">
                                                    @foreach ( $parent['children'] as $children )
                                                        <li class="dd-item" data-slug="{{ $children['slug'] }}">

                                                            @include( name_blade('Backend.permission.item'), ['data' => $children] )

                                                            @if( !empty( $children['children'] ) )

                                                                <ol class="dd-list">
                                                                    @foreach ( $children['children'] as $children )
                                                                        <li class="dd-item" data-slug="{{ $children['slug'] }}">

                                                                            @include( name_blade('Backend.permission.item'), ['data' => $children] )

                                                                        </li>
                                                                    @endforeach
                                                                </ol>

                                                            @endif

                                                        </li>
                                                    @endforeach
                                                </ol>

                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            </ol>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    @endif
</div>

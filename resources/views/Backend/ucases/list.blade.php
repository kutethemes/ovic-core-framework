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
    <h5>Danh sách menu <small>Menu Left / Menu Top</small></h5>
    <div class="ibox-tools">
        <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
        </a>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="fa fa-wrench"></i>
        </a>
        <ul id="nestable-menu" class="dropdown-menu dropdown-menu-right dropdown-order" x-placement="bottom-start">
            <li><a href="#" data-action="expand-all" class="dropdown-item">Expand All</a></li>
            <li><a href="#" data-action="collapse-all" class="dropdown-item">Collapse All</a></li>
            <li class="dropdown-divider"></li>
            <li><a href="#" data-action="add-new" class="dropdown-item add-new">Add New</a></li>
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
        <div class="row">
            @foreach( $menus as $key => $menu )
                <div class="col-sm-6{{ $key == 'menu-left' ? ' b-r' : '' }}">
                    <div id="{{ $key }}" class="dd">
                        <ol class="dd-list">
                            @if( !empty( $menu[0] ) )
                                @foreach ( $menu[0] as $parent )
                                    <li class="dd-item{{ !empty( $menu[$parent['id']] ) ? ' has-children' : '' }}"
                                        data-id="{{ $parent['id'] }}">

                                        @include( name_blade('Backend.ucases.item'), ['data' => $parent] )

                                        @if( !empty( $menu[$parent['id']] ) )

                                            <ol class="dd-list">
                                                @foreach ( $menu[$parent['id']] as $children )
                                                    <li class="dd-item" data-id="{{ $children['id'] }}">

                                                        @include( name_blade('Backend.ucases.item'), ['data' => $children] )

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
    @endif
</div>

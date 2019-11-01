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
    <h5>Danh sách menu <small>Menu Left / Menu Right</small></h5>
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
                                @php
                                    $parents = $menu[0]
                                @endphp
                                @foreach ( $parents as $parent )
                                    @php
                                        $router = json_decode($parent['router'], true)
                                    @endphp
                                    <li id="menu-{{ $parent['id'] }}" class="dd-item" data-id="{{ $parent['id'] }}">
                                        <div class="dd-handle">
                                        <span class="label label-info">
                                            <i class="{{ $router['icon'] }}"></i>
                                        </span>
                                            <div class="name">
                                                {{ $parent['title'] }}
                                            </div>
                                            <div class="dd-nodrag btn-group">
                                                <button class="btn btn-outline btn-primary edit">Edit</button>
                                                <button class="btn btn-danger remove"><i class="fa fa-trash-o"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if( !empty( $menu[$parent['id']] ) )
                                            @php
                                                $childrens  = $menu[$parent['id']];
                                            @endphp
                                            <ol class="dd-list">
                                                @foreach ( $childrens as $children )
                                                    @php
                                                        $router = json_decode($children['router'], true)
                                                    @endphp
                                                    <li class="dd-item" data-id="{{ $children['id'] }}">
                                                        <div class="dd-handle">
                                                        <span class="label label-info">
                                                            <i class="{{ $router['icon'] }}"></i>
                                                        </span>
                                                            <div class="name">
                                                                {{ $children['title'] }}
                                                            </div>
                                                            <div class="dd-nodrag btn-group">
                                                                <button class="btn btn-outline btn-primary edit">Edit
                                                                </button>
                                                                <button class="btn btn-danger remove">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </button>
                                                            </div>
                                                        </div>
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

@php
    /**
     * The left sidebar for our theme
     *
     * @var $primary_menu
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0.1
     */
    $name       = Route::currentRouteName();
    $name       = explode('.', $name, 2);
    $name       = $name[0];
    $left_menu  = $primary_menu['left'];
    $config     = [
        'ucases',
        'configs'
    ];
@endphp
<!-- Left Sidebar -->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            <li class="nav-header">
                <h1 class="profile-element font-bold m-0">
                    <a href="{{ url('/') }}">
                        {{ config('app.name','Ovic') }}
                    </a>
                </h1>
                <div class="logo-element">
                    {{ config('app.name','Ovic') }}
                </div>
            </li>

            @auth
                <li @if ( $name == 'dashboard' ) class="active" @endif>
                    <a href="{{ url('/dashboard') }}">
                        <i class="fa fa-tachometer"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
            @endauth

            @if( !empty( $left_menu ) )
                @foreach( $left_menu as $key => $parent )

                    @php
                        $active     = '';
                        $childrens  = !empty( $parent['children'] ) ? $parent['children'] : [];
                        if ( $name == $parent['slug'] ){
                            $active = 'active';
                        }
                        if ( !empty($childrens) ){
                            foreach ( $childrens as $children ) {
                                if ( $name == $children['slug'] ){
                                    $active = 'active';
                                }
                                if ( !empty($children['children']) ){
                                    foreach ( $children['children'] as $children_2 ) {
                                        if ( $name == $children_2['slug'] ){
                                            $active = 'active';
                                        }
                                    }
                                }
                            }
                        }
                    @endphp

                    <li class="{{ $active }}">
                        <a href="{{ $parent['url'] }}">
                            @if( !empty($parent['route']['icon']) )
                                <i class="{{ $parent['route']['icon'] }}"></i>
                            @endif
                            <span class="nav-label">{{ $parent['title'] }}</span>
                            @if( !empty( $childrens ) )
                                <span class="fa arrow"></span>
                            @endif
                        </a>

                        @if( !empty( $childrens ) )

                            <ul class="nav nav-second-level collapse">
                                @foreach ( $childrens as $children )

                                    @php
                                        $active_child   = '';
                                        $childrens_2    = !empty( $children['children'] ) ? $children['children'] : [];
                                        if ( $name == $children['slug'] ){
                                            $active_child = 'active';
                                        }
                                        if ( !empty($childrens_2) ){
                                            foreach ( $childrens_2 as $children_2 ) {
                                                if ( $name == $children_2['slug'] ){
                                                    $active_child = 'active';
                                                }
                                            }
                                        }
                                    @endphp

                                    <li class="{{ $active_child }}">
                                        <a href="{{ $children['url'] }}">
                                            @if( !empty($children['route']['icon']) )
                                                <i class="{{ $children['route']['icon'] }}"></i>
                                            @endif
                                            <span class="nav-label">{{ $children['title'] }}</span>
                                            @if( !empty( $children['children'] ) )
                                                <span class="fa arrow"></span>
                                            @endif
                                        </a>
                                        @if( !empty( $children['children'] ) )
                                            <ul class="nav nav-third-level">
                                                @foreach ( $children['children'] as $children )
                                                    <li @if ( $name == $children['slug'] ) class="active" @endif>
                                                        <a href="{{ $children['url'] }}">
                                                            @if( !empty($children['route']['icon']) )
                                                                <i class="{{ $children['route']['icon'] }}"></i>
                                                            @endif
                                                            <span class="nav-label">{{ $children['title'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                        @endif
                    </li>
                @endforeach
            @endif

            @auth
                <li @if ( in_array( $name, $config ) ) class="active" @endif>
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span class="nav-label">Settings</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        @if( Route::has('ucases.show') && Auth::user()->status == 3 )
                            <li @if ( $name == 'ucases' ) class="active" @endif>
                                <a href="{{ url('/ucases') }}">
                                    <i class="fa fa-codepen"></i>
                                    <span class="nav-label">Quản lý chức năng</span>
                                </a>
                            </li>
                        @endif
                        <li @if ( $name == 'configs' ) class="active" @endif>
                            <a href="{{ url('/configs') }}">
                                <i class="fa fa-wrench"></i>
                                <span class="nav-label">Cài đặt hệ thống</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</nav>

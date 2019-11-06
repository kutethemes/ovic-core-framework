@php
    /**
     * The left sidebar for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
    $name = Route::currentRouteName();
    $system = [
        'users.index',
        'roles.index',
        'ucases.index',
        'upload.index',
        'permission.index',
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

            <li @if ( $name == 'dashboard' ) class="active" @endif>
                <a href="{{ url('/dashboard') }}">
                    <i class="fa fa-tachometer"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            @if( !empty( $primary_menu['left'][0] ) )
                @foreach( $primary_menu['left'][0] as $key => $parent )
                    <li>
                        <a href="{{ url("/{$parent['slug']}") }}">
                            <i class="{{ $parent['router']['icon'] }}"></i>
                            <span class="nav-label">{{ $parent['title'] }}</span>
                            @if( !empty( $primary_menu['left'][$parent['id']] ) )
                                <span class="fa arrow"></span>
                            @endif
                        </a>

                        @if( !empty( $primary_menu['left'][$parent['id']] ) )

                            <ul class="nav nav-second-level collapse">
                                @foreach ( $primary_menu['left'][$parent['id']] as $children )
                                    <li>
                                        <a href="{{ url("/{$children['slug']}") }}">
                                            <i class="{{ $children['router']['icon'] }}"></i>
                                            <span class="nav-label">{{ $children['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        @endif
                    </li>
                @endforeach
            @endif

            <li @if ( in_array( $name, $system ) ) class="active" @endif>
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-label">Hệ thống</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li @if ( $name == 'users.index' ) class="active" @endif>
                        <a href="{{ url('/users') }}">
                            <i class="fa fa-users"></i>
                            <span class="nav-label">Quản lý người dùng</span>
                        </a>
                    </li>
                    <li @if ( $name == 'roles.index' ) class="active" @endif>
                        <a href="{{ url('/roles') }}">
                            <i class="fa fa-user-plus"></i>
                            <span class="nav-label">Nhóm người dùng</span>
                        </a>
                    </li>
                    <li @if ( $name == 'ucases.index' ) class="active" @endif>
                        <a href="{{ url('/ucases') }}">
                            <i class="fa fa-codepen"></i>
                            <span class="nav-label">Quản lý chức năng</span>
                        </a>
                    </li>
                    <li @if ( $name == 'permission.index' ) class="active" @endif>
                        <a href="{{ url('/permission') }}">
                            <i class="fa fa-key"></i>
                            <span class="nav-label">Phân quyền chức năng</span>
                        </a>
                    </li>
                    <li @if ( $name == 'upload.index' ) class="active" @endif>
                        <a href="{{ url('/upload') }}">
                            <i class="fa fa-folder-open"></i>
                            <span class="nav-label">Quản lý dữ liệu</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

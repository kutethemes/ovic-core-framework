@php
    /**
     * The left sidebar for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
    $name       = Route::currentRouteName();
    $name       = explode('.', $name, 2);
    $name       = $name[0];
    $left_menu  = $primary_menu['left'];
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

            @if( Route::has('ucases.show') && \Auth::user()->status == 3 )
                <li @if ( $name == 'ucases' ) class="active" @endif>
                    <a href="{{ url('/ucases') }}">
                        <i class="fa fa-codepen"></i>
                        <span class="nav-label">Quản lý chức năng</span>
                    </a>
                </li>
            @endif

            @if( !empty( $left_menu[0] ) )
                @foreach( $left_menu[0] as $key => $parent )
                    <li @if ( $name == $parent['slug'] ) class="active" @endif>
                        <a href="{{ url( "/{$parent['slug']}" ) }}">
                            @if( !empty($parent['route']['icon']) )
                                <i class="{{ $parent['route']['icon'] }}"></i>
                            @endif
                            <span class="nav-label">{{ $parent['title'] }}</span>
                            @if( !empty( $left_menu[$parent['id']] ) )
                                <span class="fa arrow"></span>
                            @endif
                        </a>

                        @if( !empty( $left_menu[$parent['id']] ) )

                            <ul class="nav nav-second-level collapse">
                                @foreach ( $left_menu[$parent['id']] as $children )
                                    <li @if ( $name == $children['slug'] ) class="active" @endif>
                                        <a href="{{ url( "/{$children['slug']}" ) }}">
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
            @endif

        </ul>
    </div>
</nav>

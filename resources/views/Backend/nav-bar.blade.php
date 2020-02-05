@php
    /**
     * The nav bar for our theme
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
    $toggle     = 'none';
    $top_menu   = $primary_menu['top'];
@endphp

<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                <i class="fa fa-bars"></i>
            </a>
            <span class="navbar-minimalize minimalize-styl-2">@yield('title')</span>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            @if( !empty( $top_menu ) )
                @foreach( $top_menu as $key => $parent )
                    <li>
                        @if( !empty( $parent['children'] ) )
                            @php
                                $toggle = 'dropdown';
                            @endphp
                        @endif

                        <a href="{{ $parent['url'] }}"
                           data-toggle="{{ $toggle }}"
                           class="dropdown-toggle">
                            @if( !empty($parent['route']['icon']) )
                                <i class="{{ $parent['route']['icon'] }}"></i>
                            @endif
                            <span class="nav-label">{{ $parent['title'] }}</span>
                        </a>

                        @if( !empty( $parent['children'] ) )

                            <ul class="dropdown-menu animated fadeInUp">
                                @foreach ( $parent['children'] as $children )
                                    <li @if ( $name == $children['slug'] ) class="active" @endif>
                                        <a href="{{ $children['url'] }}">
                                            @if( !empty($children['route']['icon']) )
                                                <i class="{{ $children['route']['icon'] }}"></i>
                                            @endif
                                            <span class="nav-label">{{ $children['title'] }}</span>
                                        </a>
                                    </li>
                                    @if( !empty( $children['children'] ) )

                                        @foreach ( $children['children'] as $children )
                                            <li @if ( $name == $children['slug'] ) class="active" @endif>
                                                <a href="{{ $children['url'] }}">
                                                    --
                                                    @if( !empty($children['route']['icon']) )
                                                        <i class="{{ $children['route']['icon'] }}"></i>
                                                    @endif
                                                    <span class="nav-label">{{ $children['title'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach

                                    @endif
                                @endforeach
                            </ul>

                        @endif
                    </li>
                @endforeach
            @endif
            @include( name_blade('Backend.notices.mailbox') )
            @include( name_blade('Backend.notices.notifications') )
            <li>
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="fa fa-user-circle-o"></i>
                    Hi, @auth {{ Auth::user()->name }} @else Guest @endauth
                </a>
                <ul class="dropdown-menu animated fadeInUp">
                    @auth
                        @if( Route::has('profile.index') )
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('profile.index' ) }}">
                                    Hồ sơ
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="dropdown-item">
                                Đăng nhập
                            </a>
                        </li>
                        @if ( Route::has('register') )
                            <li>
                                <a href="{{ route('register') }}" class="dropdown-item">
                                    Đăng kí
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </li>
            <li>
                <a class="right-sidebar-toggle">
                    <i class="fa fa-tasks"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

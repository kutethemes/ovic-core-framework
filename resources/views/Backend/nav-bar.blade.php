@php
    /**
     * The nav bar for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
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
            @if( !empty( $top_menu[0] ) )
                @foreach( $top_menu[0] as $key => $parent )
                    <li>
                        @if( !empty( $top_menu[$parent['id']] ) )
                            @php
                                $toggle = 'dropdown';
                            @endphp
                        @endif

                        <a href="{{ url( "/{$parent['slug']}" ) }}"
                           data-toggle="{{ $toggle }}"
                           class="dropdown-toggle">
                            <i class="{{ $parent['route']['icon'] }}"></i>
                            <span class="nav-label">
                                {{ $parent['title'] }}
                            </span>
                        </a>

                        @if( !empty( $top_menu[$parent['id']] ) )

                            <ul class="dropdown-menu animated fadeInUp">
                                @foreach ( $top_menu[$parent['id']] as $children )
                                    <li>
                                        <a href="{{ url( "/{$children['slug']}" ) }}">
                                            <i class="{{ $children['route']['icon'] }}"></i>
                                            <span class="nav-label">{{ $children['title'] }}</span>
                                        </a>
                                    </li>
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
                    Hi, {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu animated fadeInUp">
                    <li>
                        <a class="dropdown-item"
                           href="{{ route('users.show', \Auth::user()->id ) }}">
                            Profile
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

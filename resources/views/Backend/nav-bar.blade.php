@php
    /**
     * The nav bar for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
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
            <li>
                <span class="m-r-sm text-muted welcome-message">Welcome to {{ config('app.name','Ovic') }} Admin.</span>
            </li>
            @include( ovic_blade('Backend.notices.mailbox') )
            @include( ovic_blade('Backend.notices.notifications') )
            <li>
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="fa fa-user-circle-o"></i>
                    Hi, {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu animated fadeInRight m-t-xs">
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

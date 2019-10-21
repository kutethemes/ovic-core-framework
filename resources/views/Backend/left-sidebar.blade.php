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
            <li class="active">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-label">System</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li @if ( $name == 'users.index' ) class="active" @endif>
                        <a href="{{ url('/users') }}">
                            <i class="fa fa-users"></i>
                            <span class="nav-label">Users</span>
                        </a>
                    </li>
                    <li @if ( $name == 'upload.index' ) class="active" @endif>
                        <a href="{{ url('/upload') }}">
                            <i class="fa fa-folder-open"></i>
                            <span class="nav-label">Media</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

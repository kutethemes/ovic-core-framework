<?php
/**
 * The media file for our theme
 *
 * @package Ovic
 * @subpackage Framework
 *
 * @version 1.0
 */
?>
@extends( ovic_blade('Backend.app') )

@section('title', 'Users')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>Users Manager</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Users Manager</strong>
                </li>
            </ol>
        </div>
    </div>
@endsection


@php
    /**
     * The media file for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@extends( ovic_blade('Backend.app') )

@section('title', 'Media File')

@include(ovic_blade('Backend.media.content'))
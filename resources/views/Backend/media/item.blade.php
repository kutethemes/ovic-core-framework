@php
    /**
     * The media item for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @var $attachment
     *
     * @version 1.0
     */
@endphp

@php
    $url        = route( 'get_file', explode( '/', $attachment['name'] ) );
    $mimetype   = $attachment['meta']['_attachment_metadata']['mimetype'];
    $extension  = $attachment['meta']['_attachment_metadata']['extension'];
@endphp

<div class="file-box" data-id="{{ $attachment['id'] }}">
    <div class="file">
        <a href="#" class="btn-del-file" title="Xóa file">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
        </a>
        <a href="{{ $url }}" target="_blank">
            <span class="corner"></span>
            @if ( strstr( $mimetype, "video/" ) )
                <div class="icon">
                    <i class="img-fluid fa fa-film"></i>
                </div>
            @elseif ( strstr( $mimetype, "image/" ) )
                <div class="image">
                    <img alt="image" class="img-fluid" src="{{ $url }}"/>
                </div>
            @elseif ( strstr( $mimetype, "audio/" ) )
                <div class="icon">
                    <i class="fa fa-music"></i>
                </div>
            @elseif ( in_array( $extension, [ 'doc','docx' ] ) )
                <div class="icon">
                    <i class="fa fa-file-word-o"></i>
                </div>
            @elseif ( in_array( $extension, [ 'xls','xlsx' ] ) )
                <div class="icon">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
            @elseif ( strstr( $mimetype, "pdf" ) )
                <div class="icon">
                    <i class="fa fa-file-pdf-o"></i>
                </div>
            @else
                <div class="icon">
                    <i class="fa fa-file-archive-o"></i>
                </div>
            @endif
            <div class="file-name">
                <span class="name">{{ $attachment['title'] }}</span>
                <br/>
                <small>Added: {{ $attachment['created_at'] }}</small>
            </div>
        </a>
    </div>
</div>

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
    $url        = get_attachment_url( $attachment['name'], true );
    $mimetype   = $attachment['meta']['_attachment_metadata']['mimetype'];
    $extension  = $attachment['meta']['_attachment_metadata']['extension'];
@endphp

<div class="file-box image-{{ $attachment['id'] }}" data-id="{{ $attachment['id'] }}">
    <div class="file">
        @if( user_can( 'delete', 'upload' ) )
            <a href="#" class="btn-del-file" title="Xóa file">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
        @endif
        <label class="btn btn-info btn-circle">
            <i class="fa fa-check"></i>
        </label>
        <a href="{{ route('upload.show', $attachment['id']) }}" target="_blank" data-trigger="hover"
           data-toggle="popover" data-placement="top" data-content="{{ $attachment['title'] }}">
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

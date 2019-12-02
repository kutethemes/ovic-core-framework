@php
    /**
     * The media show for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @var TYPE_NAME $attachment
     *
     * @version 1.0
     */
@endphp

@php
    $url        = get_attachment_url( $attachment['name'], true );
    $size       = $attachment['meta']['_attachment_metadata']['size'];
    $mimetype   = $attachment['meta']['_attachment_metadata']['mimetype'];
    $extension  = $attachment['meta']['_attachment_metadata']['extension'];
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', $attachment['title']  )

@push( 'styles' )
    <link href="{{ asset('js/APlayer/APlayer.min.css') }}" rel="stylesheet">
    <!-- style show media -->
    <style>
        div.icon {
            font-size: 200px;
        }
    </style>
@endpush

@push( 'scripts' )
    @if ( strstr( $mimetype, "video/" ) )
        <script src="{{ asset('js/plugins/video/responsible-video.js') }}"></script>
        <!-- script show media -->
        <script>
            var body = $( 'body' );
            $( document ).on( 'webkitfullscreenchange mozfullscreenchange fullscreenchange', function ( e ) {
                body.hasClass( 'fullscreen-video' ) ? body.removeClass( 'fullscreen-video' ) : body.addClass( 'fullscreen-video' )
            } );
        </script>
    @endif
    @if ( strstr( $mimetype, "audio/" ) )
        <script src="{{ asset('js/APlayer/APlayer.min.js') }}"></script>
        <!-- script show media -->
        <script>
            const ap = new APlayer( {
                container: document.getElementById( 'aplayer' ),
                audio: [ {
                    name: "{{ $attachment['title'] }}",
                    artist: 'artist',
                    url: "{{ $url }}",
                    cover: 'cover.jpg'
                } ]
            } );
        </script>
    @endif
@endpush

@section( 'content' )

    <div class="col-sm-12 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-title">
                <h5>THÔNG TIN FILE</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-7 b-r">
                        <div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Title</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">{{ $attachment['name'] }}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Time created</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">{{ $attachment['created_at'] }}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Url</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        <a href="{{ $url }}">
                                            {{ $url }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Local Url</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        storage/app/uploads/{{ $attachment['name'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">File size</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">
                                        {{ $size }}
                                    </p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="text-center">
                            @if ( strstr( $mimetype, "video/" ) )
                                <figure>
                                    <iframe width="425" height="349" src="{{ $url }}" frameborder="0"
                                            allowfullscreen></iframe>
                                </figure>
                            @elseif ( strstr( $mimetype, "image/" ) )
                                <div class="image">
                                    <img alt="image" class="img-fluid" src="{{ $url }}"/>
                                </div>
                            @elseif ( strstr( $mimetype, "audio/" ) )
                                <div class="icon">
                                    <i class="fa fa-music"></i>
                                </div>
                                <div id="aplayer"></div>
                            @elseif ( in_array( $extension, [ 'doc','docx' ] ) )
                                <div class="icon">
                                    <i class="fa fa-file-word-o"></i>
                                </div>
                            @elseif ( in_array( $extension, [ 'ppt','pptx' ] ) )
                                <div class="icon">
                                    <i class="fa fa-file-powerpoint-o"></i>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

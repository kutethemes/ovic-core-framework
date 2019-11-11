@php
    /**
     * The media show for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @var $attachment
     *
     * @version 1.0
     */
@endphp

@extends( name_blade('Backend.app') )

@section( 'title', 'Attachment Detail' )

@push( 'styles' )
    <style>
        div.icon {
            font-size: 200px;
        }
    </style>
@endpush

@section( 'content' )

    @php
        $url        = get_attachment_url( $attachment['name'], true );
        $size       = $attachment['meta']['_attachment_metadata']['size'];
        $mimetype   = $attachment['meta']['_attachment_metadata']['mimetype'];
        $extension  = $attachment['meta']['_attachment_metadata']['extension'];
    @endphp

    <div class="col-sm-12 full-height">
        <div class="ibox full-height-scroll">
            <div class="ibox-title">
                <h5>{{ $attachment['name'] }}</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-7 b-r">
                        <div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Title</label>
                                <div class="col-lg-9">
                                    <p class="form-control-static">{{ $attachment['title'] }}</p>
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
                        <a href="{{ $url }}" class="text-center" target="_blank">
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
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

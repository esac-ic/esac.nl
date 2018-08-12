@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="/css/plugins/photoswipe/photoswipe.css">
    <link rel="stylesheet" href="/css/plugins/photoswipe/default-skin/default-skin.css">

    {{--TODO: Move prev links--}}

    <div class="container">
        <div class="row">
            <div class="col-md-12 block-main">
                {{--Start of custom text--}}
                <h1>Photo galleries</h1>
                {{--End of custom text--}}
            </div>
        </div>
        <div class="row gallery" id="gallery">
        </div>
    </div>
@endsection

@section('postscripts')
    <script src="/js/plugins/photoswipe.min.js"></script>
    <script src="/js/plugins/photoswipe-ui-default.min.js"></script>

    <script language="JavaScript">
        var cnt = {
            close: '{{trans('front-end/Gallery.close')}}',
            share: '{{trans('front-end/Gallery.share')}}',
            fullScreen: '{{trans('front-end/Gallery.fullScreen')}}',
            zoom: '{{trans('front-end/Gallery.zoom')}}',
            left: '{{trans('front-end/Gallery.left')}}',
            right: '{{trans('front-end/Gallery.right')}}',
            back: '{{trans('front-end/Gallery.back')}}'
        };
    </script>
    <script src="/js/photoswipe.js"></script>
@endsection

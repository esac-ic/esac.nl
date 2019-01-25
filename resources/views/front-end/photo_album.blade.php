@javascript('photos', $photos)
@javascript('photoAlbum', $photoAlbum)
<meta name="csrf-token" content="{{ csrf_token() }}">

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/default-skin/default-skin.css">
@endpush

@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#AddPhotoModal">{{trans('front-end/photo.addPhoto')}}<div class="float-right pl-1 icon ion-android-add"></div></button>
            <h2 class="card-title">{{$photoAlbum->title}}</h2>
            <p id="albumDescription"> {!! nl2br($photoAlbum->description) !!} </p>
        </div>
    </div>
</div>
<section class="py-3">
    <div class="container">

        <!-- Root element of PhotoSwipe. Must have class pswp. -->
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <!-- Background of PhotoSwipe. 
                It's a separate element as animating opacity is faster than rgba(). -->
            <div class="pswp__bg"></div>
            <!-- Slides wrapper with overflow:hidden. -->
            <div class="pswp__scroll-wrap">
                <!-- Container that holds slides. 
                    PhotoSwipe keeps only 3 of them in the DOM to save memory.
                    Don't modify these 3 pswp__item elements, data is added later on. -->
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>
                <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <!--  Controls are self-explanatory. Order can be changed. -->
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--share" title="Share"></button>
                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                        <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                        <!-- element will get class pswp__preloader--active when preloader is running -->
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div> 
                    </div>
                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                    </button>
                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                    </button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row no-gutters">
                    @foreach ($photos as $id=>$photo)
                        <div class="col-md-4 col-sm-6 p-1">
                            <img class="w-100 hover-lighten c-pointer" src="{!! $photo->thumbnail !!}" onclick="openGallery('{{ $loop->index }}');">
                        </div>
                    @endforeach
                </div>
            </div>   
        </div>
    </div>
    {{ $photos->links('front-end.pagination') }}
</section>

<div id="AddPhotoModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{trans('front-end/photo.addPhoto')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group"> 
                        <label class="input-group-btn">
                            <span class="btn btn-primary">
                            {{trans('front-end/photo.browse')}}&hellip; <input style="display: none;" class="form-control" type="file" id="file-select" name="photos[]" multiple required/>
                            </span>
                        </label>
                        <input id="filesSelected" type="text" class="form-control" readonly>
                    </div>
                    <div id="progress" style="visibility:hidden" class="progress">       
                            <div id="progressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            <small id="progressText" class="justify-content-center d-flex position-absolute w-100">0% complete</small>
                    </div>  
                    <div class="modal-footer">
                        <button class="btn btn-primary" id='submit' onclick="uploadPhotos()">{{trans('front-end/photo.add')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('front-end/photo.close')}}</button>
                    </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{mix("js/photoswipe.min.js")}}"></script>
    <script src="{{mix("js/photoswipe-ui-default.min.js")}}"></script>
    <script src="{{mix("js/photoAlbum.js")}}"></script>
    <script src="{{mix("js/load-image.all.min.js")}}"></script>
    <script>
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    $(document).ready( function() {
        $(':file').on('fileselect', function(event, numFiles, label) {

            var input = $('#filesSelected').val(numFiles + " files selected");
        });
    });
    </script>
@endpush
@endsection



 
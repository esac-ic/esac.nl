<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/default-skin/default-skin.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.2/photoswipe-ui-default.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/pica/4.1.1/pica.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pica/4.1.1/pica.min.js"></script>

</head>
@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{$photoAlbum->title}}</h2>
            {{$photoAlbum->description}}
        </div>
    </div>
</div>
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
            <div >
                @foreach ($thumbnails as $id=>$thumbnail)
                    <img class="col-*-* px-1" src="{!! $thumbnail !!}" onclick="openGallery('{{ $loop->index }}');">
                @endforeach
            </div>
            
            <div class="mt-2">
                <h5>Foto's toevoegen </h5>
                <div class="mt-1">
                    <form action="{{$photoAlbum->id}}/upload" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row cancel">  
                        <div class="col-md-4">
                            <input required type="file" class="form-control" name="images[]" placeholder="address" multiple>        
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Toevoegen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>
@javascript('photos', $photos);
@endsection



 
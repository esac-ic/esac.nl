@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{mix('/css/vendor/photoswipe.css')}}">
@endpush

@section('content')
    {{--TODO: Move prev links--}}

        <div class="container intro-container">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="card-title">{{$curPageName}}</h2>
                        </div>
                        <div class="col-md-6">
                            <a href="https://www.flickr.com/photos/150450369@N02/" class="btn btn-outline-primary float-md-right">
                                <span title="Foto's uploaden" class="ion-plus" aria-hidden="true"></span>
                                Foto's uploaden
                            </a>
                        </div>
                    </div>
                    {{--Start of custom text--}}
                    <p class="card-text">De foto's zijn gekoppeld aan het ESAC Flickr account. Om foto's te uploaden moet je inloggen op dit account, je kunt hier ook alle albums downloaden. Als je de inloggegevens bent vergeten graag even contact opnemen met het bestuur.</p>
                    {{--End of custom text--}}
                </div>
            </div> 
        </div>

        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="gallery" id="gallery"></div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <script src="{{mix("/js/vendor/photoswipe.js")}}"></script>
    <script language="JavaScript">

        /**
         * Photoswipe JS
         */

        const FL_USER_ID = "150450369@N02";
        const FL_PUBLIC = "6debde82bb7b53ca099a6461a711c7f6";

        //flags for flickr files
        const flags = {
            s: 75,
            q: 150,
            t: 100,
            m: 240,
            n: 320,
            none: 500,
            z: 640,
            b: 1024,
            o: 0
        };

        //json for rebuilding the gallery screen
        var galleryJson;

        //photoswipe gallery
        var pswpGallery = "Not defined";
        var photoSlides;

        // Loaded var for checking if an album is yet already loaded.
        var loaded = {
            pictures: false,
            dom: false,
            all: false
        };

        // Start when finished loading
        $('document').ready(function () {
            getFlickrData("photosets.getList", {extras: "original_format,url_o"}, function(json) {
                galleryJson = json;
                printAlbums(galleryJson);
            });

            addClickEventThumbnails();
        });

        function getFlickrData(_method, _params, _succesFunc)
        {
            var ajaxCall = {
                url: "https://api.flickr.com/services/rest/?method=flickr." + _method,
                method: "GET",
                data: Object.assign({
                    api_key: FL_PUBLIC,
                    user_id: FL_USER_ID,
                    format: "json",
                    nojsoncallback: "?"
                }, _params)
            };

            $.ajax(ajaxCall)
                .done(function(json) {
                    _succesFunc(json);
                });
        }

        function getPhotoSRC(photo, flag)
        {
            var id = photo.primary == undefined ? photo.id : photo.primary;
            var debug;
            if(flag != 'o') {
                debug = "https://farm" + photo.farm + ".staticflickr.com/" + photo.server + "/" + id + "_" + photo.secret + "_" + flag + ".jpg";
            } else {
                debug = photo.url_o;
            }
            return debug;
        }

        function loadAlbum(albumId)
        {
            loaded.pictures = false;
            loaded.dom = false;
            loaded.all = false;

            var album;

            // Load picture links
            var data = {
                extras: "original_format,url_o",
                photoset_id: albumId
            };
            getFlickrData("photosets.getPhotos", data,
                function (json) {
                    album = json;
                    loaded.pictures = true;

                    if(loaded.dom) {
                        setUpPhotoSwipe(json);
                    } else {
                        albumWaiter(json);
                    }
                });

            // Meanwhile {
            // set up DOM material
            $('.gallery')
                .html('') // clear dom
                .append(buildAlbumHeaderDOM()) // Set up header
                .append(buildPhotoSwipeDOM()) // Set up photoSwipe
                .append($('<div>', {'class':'album-container row'})); // Set up container for photos
            loaded.dom = true;
            // } end meanwhile

            // Other things
        }

        //In case loadAlbum misses the oppertunity to set the album, this recursively checks if everything is finished
        function albumWaiter(json)
        {
            //first check if setAlbum hasn't ran yet
            if(!loaded.all) {
                // if one of the two is still loading
                if(!loaded.dom || !loaded.pictures) {
                    setTimeout(function() { albumWaiter(json) }, 100);
                } else {
                    //if both are true
                    setUpPhotoSwipe(json);
                }
            }
        }

        function setUpPhotoSwipe(json)
        {
            loaded.all = true;

            //fill in metadata for album
            $('.gallery .header .album-meta #album-title').html(json.photoset.title);
        //            $('#albumInfo').html(json->description);

            //create slides json
            var slides = [];
            for (var i = 0; i < json.photoset.photo.length; i++) {
                var photo = json.photoset.photo[i];
                var slide = {
                    src: getPhotoSRC(photo, 'o'),
                    w: getPhotoWidth(photo.width_o, photo.height_o, 'o'),
                    h: getPhotoHeight(photo.width_o, photo.height_o, 'o'),

                    msrc: getPhotoSRC(photo, 'q'),

                    title: photo.title
                };

                slides.push(slide);
            }

            photoSlides = slides;

            appendPhotos(slides);
            addClickEventPictures();
            addClickEventGoBackButton();
        }

        function gotoPhotoswipeSlide(slideId)
        {
            //Photoswipe options
            var pswpOptions = {
                index: parseInt(slideId, 10),
                closeOnScroll: true,
                preload: [3, 3],
                loop: false,
                showHideOpacity: true,
                getThumbBoundsFn: false
            };

            pswpGallery = new PhotoSwipe($('.pswp')[0], PhotoSwipeUI_Default, photoSlides, pswpOptions);
            pswpGallery.init();
        }

        function getPhotoWidth(width, height, flag)
        {
            if (flag == "o") {
                return width;
            } else {
                if(width > height) {
                    return flags[flag];
                } else {
                    return width * flags[flag] / height;
                }
            }
        }

        function getPhotoHeight(width, height, flag)
        {
            if(flag == "o") {
                return height;
            } else {
                if(height > width) {
                    return flags[flag];
                } else {
                    return heigt * flags[flag] / height;
                }
            }
        }

        // ###################################################################################
        // dom manupulation functions
        // ###################################################################################

        function printAlbums(data)
        {
            data = data.photosets.photoset;
            for(var i = 0; i < data.length; i++) {
                $('#gallery')
                    .append($('<div>', {"class":"photo-item col-sm-3 col-xs-6", "data-id":data[i].id})
                        .append($('<div>', {"class":"album block-image"})
                            .append($('<img>', {src:getPhotoSRC(data[i], 'n')}))
                            .append($('<div>', {'class':'block-image-text'})
                                .append($('<h3>').html(data[i].title._content))
                                .append($('<h3>').html(data[i].description._content))
                            )
                        )
                    );
            }
        }


        function buildAlbumHeaderDOM()
        {
            return $('<div>', {'class':'row header'})
                .append($('<div>', {'class':'album-meta block-main col-xs-12 col-sm-8'})
                    .append($('<h2>', {id:'album-title'}))
                    .append($('<p>', {id:'album-info'}))
                ).append($('<div>', {'class':'block-main col-xs-12 col-sm-4'})
                    .append($('<button>', {'class':'btn btn-default col-xs-12 col-sm-12', id:'album-back-button', value: cnt.back, title: cnt.back}).html(cnt.back))
        );
        }

        function buildPhotoSwipeDOM()
        {
            return $('<div>', {
                'class': 'pswp',
                tabindex:'-1',
                role:'dialog',
                'aria-hidden':'true'
            })
                .append($('<div>',{'class':'pswp__bg'}))
                .append($('<div>',{'class':'pswp__scroll-wrap'})
                    .append($('<div>',{'class':'pswp__container'})
                        .append($('<div>',{'class':'pswp__item'}))
                        .append($('<div>',{'class':'pswp__item'}))
                        .append($('<div>',{'class':'pswp__item'})))
                    .append($('<div>', {'class':'pswp__ui pswp__ui--hidden'})
                        .append($('<div>', {'class':'pswp__top-bar'})
                            .append($('<div>', {'class':'pswp__counter'}))
                            .append($('<button>', {
                                'class':'pswp__button pswp__button--close',
                                title: cnt.close}))
                            .append($('<button>', {
                                'class':'pswp__button pswp__button--share',
                                title: cnt.share}))
                            .append($('<button>', {
                                'class':'pswp__button pswp__button--fs',
                                title: cnt.fullScreen}))
                            .append($('<button>', {
                                'class':'pswp__button pswp__button--zoom',
                                title: cnt.zoom}))
                            .append($('<div>', {'class':'pswp__preloader'})
                                .append($('<div>', {'class':'pswp__preloader__icn'})
                                    .append($('<div>', {'class':'pswp__preloader__cut'})
                                        .append($('<div>', {'class':'pswp__preloader__donut'}))
                                    )
                                )
                            )
                        )
                        .append($('<div>', {'class':'pswp__share-modal pswp__share-modal--hidden pswp__single-tap'})
                            .append($('<div>', {'class':'pswp__share-tooltip'}))
                        )
                        .append($('<button>', {
                            'class':'pswp__button pswp__button--arrow--left',
                            title: cnt.left
                        }))
                        .append($('<button>', {
                            'class':'pswp__button pswp__button--arrow--right',
                            title: cnt.right
                        }))
                        .append($('<div>', {'class':'pswp__caption'})
                            .append($('<div>', {'class':'pswp__caption__center'}))
                        )
                    )
                );
        }

        function appendPhotos(photos)
        {
            for(var i = 0; i < photos.length; i++) {
                $('.album-container')
                    .append($('<div>', {'class':'album-image-outer col-xs-6 col-sm-3', "data-nmr":i})
                        .append($('<div>', {'class':'album-image'})
                            .append($('<img>', {src:photos[i].msrc}))
                        )
                    );
            }
        }

        // ###################################################################################
        // dom event functions
        // ###################################################################################

        function addClickEventThumbnails()
        {
            $('.gallery')
                .on('click','div.photo-item', function () {
                    loadAlbum($(this).attr("data-id"));
                });
        }

        function addClickEventPictures()
        {
            $('.gallery .album-container')
                .on('click', 'div.album-image-outer', function () {
                    gotoPhotoswipeSlide($(this).attr("data-nmr"));
                });
        }

        function addClickEventGoBackButton()
        {
            $('#album-back-button')
                .on('click', function() {
                    // Clear gallery
                    $('.gallery').html('');
                    printAlbums(galleryJson);
                });
        }
        
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
@endpush

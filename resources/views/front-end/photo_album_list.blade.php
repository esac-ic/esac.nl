@push('styles')

@endpush
@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#AddAlbumModal">{{trans('front-end/photo.addAlbum')}}</button>
                <h2 class="card-title">Foto albums der Esac</h2>
                Hier zijn fotos van klimweekenden, zomertochtjes en borrels te vinden!
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach ($photoAlbums as $album)
                <div class="col-sm-4 d-flex flex-wrap">
                    <div class="card w-100 position-relative">
                        <a href="photoalbums/{!! $album->id !!}">
                            <img class="card-img-top" src="{!! $album->thumbnail !!}" alt="Card image cap">
                        </a>
                        <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{\Carbon\Carbon::parse($album->date)->format('d M Y')}}</span>
                        <div class="card-body">
                            <a href="photoalbums/{!! $album->id !!}">
                                <h4 class="card-title">{!! $album->title !!}</h4>
                                <p class="card-text text-body">{!! nl2br($album->description) !!}</p>
                            </a>
                        </div>
                        <div class="card-footer bg-white p-3">
                            <a class="btn btn-outline-primary" href="photoalbums/{!! $album->id !!}">{{trans('front-end/photo.show')}}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>           
    </div>
    {{ $photoAlbums->links('front-end.pagination') }}

    <div id="AddAlbumModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{trans('front-end/photo.addAlbum')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" id="inputTitle" type="text" name="title" placeholder="{{trans('front-end/photo.title')}}" required/>
                    </div>
                    <div class="form-group"> 
                        <textarea class="form-control" id="textareaDescription" type="" name="description" placeholder="{{trans('front-end/photo.description')}}" required></textarea> 
                    </div>
                    <div class=form-group>
                        {{trans('front-end/photo.date')}}
                        <div class="input-group date" id="CaptureDateBox" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="CaptureDate" name="Date" data-target="#CaptureDateBox" value='{{date("j-m-Y")}}' required="">
                            <div class="input-group-append" data-target="#CaptureDateBox" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="ion-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="input-group-btn">
                            <span class="btn btn-primary">
                            {{trans('front-end/photo.browse')}}&hellip; <input style="display: none;" class="form-control" type="file" id="file-select" name="photos[]" multiple required/>
                            </span>
                        </label>
                        <input id="filesSelected" type="text" class="form-control" readonly>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id='submit' onclick="uploadPhoto()">{{trans('front-end/photo.add')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('front-end/photo.close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{mix("js/vendor/moment.js")}}" type="text/javascript"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
    <script src="../js/photoalbum.js"></script>
    <script src="../js/load-image.all.min.js"></script>
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

    $('#CaptureDateBox').datetimepicker({
        locale: 'nl',
        format: "DD-MM-YYYY",
        icons: {
            time: 'ion-clock',
            date: 'ion-calendar',
            up: 'ion-android-arrow-up',
            down: 'ion-android-arrow-down',
            previous: 'ion-chevron-left',
            next: 'ion-chevron-right',
            today: 'ion-calendar',
            clear: 'ion-trash-a',
            close: 'ion-close'
        }
    });
    </script>
@endpush






 
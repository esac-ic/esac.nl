@push('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
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
                        <div class="card-body">
                            <a href="photoalbums/{!! $album->id !!}">
                                <h4 class="card-title">{!! $album->title !!}</h4>
                                <p class="card-text text-body">{!! nl2br($album->description) !!}</p>
                            </a>
                        </div>
                        <div class="card-footer bg-white p-3">
                            <a class="btn btn-outline-primary" href="photoalbums/{!! $album->id !!}">Zie meer</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>           
    </div>
    {{ $photoAlbums->links('front-end.pagination') }}

    <div class="container">
        <div class="mt-2 mt-auto">
            <h5>Album toevoegen </h5>
                <div class="form-group">
                    <input class="form-control" id="inputTitle" type="text" name="title" placeholder="Album naam" required/>
                </div>
                <div class="form-group"> 
                    <textarea class="form-control" id="textareaDescription" type="" name="description" placeholder="Album beschrijving" required></textarea> 
                </div>
                <div class="form-group"> 
                    <label class="input-group-btn">
                        <span class="btn btn-primary">
                            Browse&hellip; <input style="display: none;" class="form-control" type="file" id="file-select" name="photos[]" multiple required/>
                        </span>
                    </label>
                    <input id="filesSelected" type="text" class="form-control" readonly>
                </div>
                    <button class="btn btn-primary" id='submit' onclick="uploadPhoto()">Toevoegen</button>
            </div>
        </div>  
    </div>
@endsection

@push('scripts')
    <script src="../js/photoalbum.js"></script>
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






 
@extends('layouts.app')

@section('content')
<form action="{{$photoAlbum->id}}/upload" method="POST" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="row cancel">
    
    <div class="col-md-4">
        <input required type="file" class="form-control" name="images[]" placeholder="address" multiple>        
    </div>

    <div class="col-md-4">

        <button type="submit" class="btn btn-success">Create</button>

    </div>

</div>


{!! Form::close() !!}

@endsection

 
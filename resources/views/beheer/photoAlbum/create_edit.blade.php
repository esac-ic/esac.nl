@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{$fields['title']}}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            <div class="form-group">
                {!! Form::label('title', trans('photoAlbum.title')) !!}
                {!! Form::text('title', ($photoAlbum != null) ? $photoAlbum->title : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('description', trans('photoAlbum.description')) !!}
                    {!! Form::text('description', ($photoAlbum != null) ? $photoAlbum->description : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                {!! Form::label('date', trans('photoAlbum.date')) !!}
                <div class="input-group date" id="dateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="date" name="date" data-target="#dateBox" value="{{($photoAlbum != null) ? \Carbon\Carbon::parse($photoAlbum->date)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')}}" required/>
                     <div class="input-group-append" data-target="#dateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($photoAlbum == null) ? ('/photoAlbums') : ('/photoAlbums/' . $photoAlbum->id)}}">{{trans('menu.cancel')}}</a>
    </div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/moment.js")}}"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}"></script>
<script>
    $('#dateBox').datetimepicker({
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
@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <div id="app">
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

        {!! Form::open(['method' => $fields['method'], 'url' => $fields['url'],'enctype' => 'multipart/form-data'])  !!}
        <div class="card">
            <div class="card-header">
                <h3>{{$fields['title']}}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    {!! Form::label('title', trans('NewsItem.titleEn')) !!}
                    {!! Form::text('title', ($newsItem != null) ? $newsItem->title : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('author', trans('NewsItem.author')) !!}
                    <auto-complete-field
                            name="author"
                            url="{{ route('user.autoComplete') }}"
                            placeholder="{{ trans('NewsItem.author') }}"
                            value="{{ ($newsItem != null) ? $newsItem->author : "" }}">
                    </auto-complete-field>
                </div>
                <span>{{trans("NewsItem.newsImage")}}</span>
                <div class="form-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('text',  trans('NewsItem.textEn'))}}
                    {{Form::textarea('text',($newsItem != null) ?  $newsItem->text : "",array('class' => 'form-control', 'id' => 'content_en'))}}
                </div>
            </div>
        </div>

        <div class="my-4">
            {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
            {!! Form::close() !!}
            <a class="btn btn-danger btn-close" href="{{ ($newsItem == null) ? ('/newsItems') : ('/newsItems/' . $newsItem->id)}}">{{trans('menu.cancel')}}</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.custom-file-input').on('change', function() {
           let fileName = $(this).val().split('\\').pop();
           $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#content_nl').summernote(summernoteSettings);
            $('#content_en').summernote(summernoteSettings);
        });
    </script>
@endpush

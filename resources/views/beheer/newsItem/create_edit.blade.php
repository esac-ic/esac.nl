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

        {{ html()->form($fields['method'], $fields['url'])->attribute('enctype', 'multipart/form-data')->open() }}
        <div class="card">
            <div class="card-header">
                <h3>{{$fields['title']}}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    {{ html()->label('Title', 'title') }}
                    {{ html()->text('title')
                        ->value(($newsItem != null) ? $newsItem->title : "")
                        ->class('form-control')
                        ->required() }}
                </div>
                <div class="form-group">
                    {{ html()->label('Author', 'author') }}
                    <auto-complete-field
                            name="author"
                            url="{{ route('user.autoComplete') }}"
                            placeholder="Author"
                            value="{{ ($newsItem != null) ? $newsItem->author : "" }}">
                    </auto-complete-field>
                </div>
                <span>{{'Image'}}</span>
                <div class="form-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label('News message', 'text') }}
                    {{ html()->textarea('text')
                        ->value(($newsItem != null) ? $newsItem->text : "")
                        ->class(['form-control'])
                        ->id('content') }}
                </div>
            </div>
        </div>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
            {{ html()->form()->close() }}
            <a class="btn btn-danger btn-close" href="{{ ($newsItem == null) ? ('/newsItems') : ('/newsItems/' . $newsItem->id)}}">Cancel</a>
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
            $('#content').summernote(summernoteSettings);
        });
    </script>
@endpush

@extends('layouts.beheer')

@section('title')
{{trans("frontEndReplacement.pageTitle")}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans("frontEndReplacement.pageTitle")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('frontEndReplacement/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("frontEndReplacement.new")}}
                </a>
            </div>
        </div>
    </div>
    <p>
        {{trans("frontEndReplacement.explanation")}}
    </p>
    <hr/>
    <table id="newsItems" class="table table-striped dt-responsive nowrap">
        <thead>
        <tr>
            <th>{{trans('frontEndReplacement.title')}}</th>
            <th>{{trans('frontEndReplacement.text')}}</th>
            <th>{{trans('frontEndReplacement.email')}}</th>
            <th>{{trans('frontEndReplacement.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($frontEndReplacements as $frontEndReplacement)
            <tr>
                <td>{{$frontEndReplacement->word}}</td>
                <td>{{$frontEndReplacement->replacement}}</td>
                <td>{{$frontEndReplacement->email}}</td>
                <td>

                    {!! Form::open(['onsubmit' => 'return confirm("'.trans("frontEndReplacement.confirm").'")','method' => 'DELETE', 'url' => ['frontEndReplacement/'. $frontEndReplacement->id]]) !!}
                    {{--<a href="{{url('/frontEndReplacement/' . $frontEndReplacement->id . '/edit')}}"><span title="{{trans('frontEndReplacement.edit')}}" class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>--}}
                        {{Form::button('<em class="ion-trash-a"></em>', ['type' => 'submit', 'class' => 'btn btn-xs btn-danger'])}}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#newsItems').dataTable({
                @if(App::isLocale('nl'))
                "language": {
                    "url": "js/dutch.lang"
                }
                @endif
            });
        });
    </script>
@endpush
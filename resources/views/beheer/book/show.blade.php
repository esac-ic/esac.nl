@extends('layouts.beheer')

@section('title')
{{trans("book.books")}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans("book.books")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/books/'.$book->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{url('/books/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'books/' . $book->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('book.book')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{trans('book.title')}}</td>
                    <td>{{ $book->title }}</td>
                </tr>
                <tr>
                    <td>{{trans('book.year')}}</td>
                    <td>{{ $book->year }}</td>
                </tr>
                <tr>
                    <td>{{trans('book.country')}}</td>
                    <td>{{ $book->country }}</td>
                </tr>
                <tr>
                    <td>{{trans('book.type')}}</td>
                    <td>{{ $book->type }}</td>
                </tr>
                <tr>
                    <td>{{trans('book.code')}}</td>
                    <td>{{ $book->code }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
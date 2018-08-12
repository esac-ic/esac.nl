@extends('layouts.beheer')

@section('title')
{{$applicationForm->applicationFormName->text()}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$applicationForm->applicationFormName->text()}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/applicationForms/'.$applicationForm->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{url('/applicationForms/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'applicationForms/' . $applicationForm->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('ApplicationForm.applicationformRows')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <thead>
                <th>{{trans('ApplicationForm.name')}}</td>
                <th>{{trans('ApplicationForm.rowType')}}</td>
                <th>{{trans('ApplicationForm.rowRequired')}}</td>
                </thead>
                <tbody>
                @foreach($applicationForm->getApplicationFormRows as $row)
                    <tr>
                        <td>{{$row->applicationFormRowName->text()}}</td>
                        <td>{{$row->type}}</td>
                        <td>{{($row->required == 1)? trans('menu.yes'): trans('menu.no')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
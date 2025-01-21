@extends('layouts.beheer')

@section('title')
{{$applicationForm->name}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$applicationForm->name}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{route('beheer.applicationForms.edit', $applicationForm->id)}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{route('beheer.applicationForms.index')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
                {{ Form::open(array('url' => route('beheer.applicationForms.destroy', $applicationForm->id), 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Form rows'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <thead>
                <th>{{'Name'}}</td>
                <th>{{'Row type'}}</td>
                <th>{{'Value required'}}</td>
                <th>{{'Options'}}</td>
                </thead>
                <tbody>
                @foreach($applicationForm->applicationFormRows as $row)
                    <tr>
                        <td>{{$row->name}}</td>
                        <td>{{$row->type}}</td>
                        <td>{{($row->required == 1)? 'Yes': 'No'}}</td>
                        <td>
                            @if ($row->applicationFormRowOptions->count() > 0)
                                <ul>
                                    @foreach($row->applicationFormRowOptions as $rowOption)
                                        <li>{{ $rowOption->name }} - {{ $rowOption->value }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

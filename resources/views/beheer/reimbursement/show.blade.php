@extends('layouts.beheer')

@section('title')
{{'Event general information'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Agenda item</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/reimbursement/'.$reimbursement->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/agendaItems/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{'Back'}}
                    </a>
                @endif
                <form method="post"
                      action="{{ route("reimbursement.destroy", $reimbursemnt) }}"
                      onsubmit="return confirm('Are you sure you want to delete this reimbursement?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Event general information'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{'Title'}}</td>
                    <td>{{$agendaItem->title}}</td>
                </tr>
                <tr>
                    <td>{{'Short description'}}</td>
                    <td>{{$agendaItem->shortDescription}}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection

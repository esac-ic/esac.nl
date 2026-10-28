@extends('layouts.beheer')

@section('title')
{{'Event category'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Event category'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/agendaItemCategories/'.$agendaItemCategory->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{url('/agendaItemCategories/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> Back
                </a>
                <form method="post"
                      action="{{ route("agendaItemCategories.destroy", $agendaItemCategory) }}"
                      onsubmit="return confirm('Are you sure you want to delete this agenda item category?');"
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
            <h3>{{'Event category'}}</h3>
        </div>
        <div class="card-body">
            {{'Name'. ": " . $agendaItemCategory->name}}
        </div>
    </div>
@endsection

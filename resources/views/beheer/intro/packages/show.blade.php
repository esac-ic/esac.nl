@extends('layouts.beheer')

@section('title')
{{ 'Intro packages' }}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Intro packages'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('beheer.intro.packages.edit', $package) }}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{ route('beheer.intro.packages.index') }}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
                {{ Form::open(array('url' => route('beheer.intro.packages.destroy', $package), 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{ 'Intro packages' }}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <b>@lang('intro.packageName') EN</b>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ $package->name }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <b>@lang('intro.packageDeadline')</b>
                </div>
                <div class="form-group col-md-6">
                    <b>@lang('intro.packageForm')</b>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ $package->deadline->format('d-m-Y') }}
                </div>
                <div class="form-group col-md-6">
                    {{ $package->applicationForm->name }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}"></script>
<script>
</script>
@endpush

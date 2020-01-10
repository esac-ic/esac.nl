@extends('layouts.beheer')

@section('title')
{{ trans("menu.introPackages") }}
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
            <h1>{{trans("menu.introPackages")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('beheer.intro.packages.edit', $package) }}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{ route('beheer.intro.packages.index') }}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => route('beheer.intro.packages.destroy', $package), 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{ trans("menu.introPackages") }}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <b>@lang('intro.packageName') NL</b>
                </div>
                <div class="form-group col-md-6">
                    <b>@lang('intro.packageName') EN</b>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ $package->packageName->NL_text }}
                </div>
                <div class="form-group col-md-6">
                    {{ $package->packageName->EN_text }}
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
                    {{ $package->applicationForm->applicationFormName->text() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
<script>
</script>
@endpush

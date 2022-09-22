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
            <h1>{{ trans("menu.introPackages") }}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('beheer.intro.packages.create') }}" class="btn btn-primary">
                    <span title="{{ trans("intro.packageCreate") }}" class="ion-plus" aria-hidden="true"></span>
                    {{ trans("intro.packageCreate") }}
                </a>
            </div>
        </div>
    </div>

    <table class="table table-striped w-100" id="packages_table">
        <thead>
        <tr>
            <td>
                <strong>{{trans('intro.packageName')}}</strong>
            </td>
            <td>
                <strong>{{trans('intro.packageDeadline')}}</strong>
            </td>
            <td>
                <strong>{{trans('menu.action')}}</strong>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach ($packages as $package)
            <tr>
                <td>{{ $package->packageName->text() }}</td>
                <td>{{ $package->deadline->format('d-m-Y') }}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{ route('beheer.intro.packages.edit', $package) }}"><span title="{{trans('intro.packageEdit')}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{ route('beheer.intro.packages.show', $package) }}"><span title="{{trans("intro.packageShow")}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}"></script>
<script>
    $('#registrations_table').DataTable();
</script>
@endpush

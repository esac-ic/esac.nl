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
            <h1>{{ 'Intro packages' }}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('beheer.intro.packages.create') }}" class="btn btn-primary">
                    <span title="{{ 'New introduction package' }}" class="ion-plus" aria-hidden="true"></span>
                    {{ 'New introduction package' }}
                </a>
            </div>
        </div>
    </div>

    <table class="table table-striped w-100" id="packages_table">
        <thead>
        <tr>
            <td>
                <strong>{{'Name'}}</strong>
            </td>
            <td>
                <strong>{{'Registration deadline'}}</strong>
            </td>
            <td>
                <strong>{{'Action'}}</strong>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach ($packages as $package)
            <tr>
                <td>{{ $package->name }}</td>
                <td>{{ $package->deadline->format('d-m-Y') }}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{ route('beheer.intro.packages.edit', $package) }}"><span title="{{'Edit package'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{ route('beheer.intro.packages.show', $package) }}"><span title="Show package" class="ion-eye font-size-120" aria-hidden="true"></span></a>
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

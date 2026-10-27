@extends('layouts.beheer')

@section('title')
    {{"Committees"}}
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{"Committees"}}</h1>
        </div>
        
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{route("beheer.committees.create")}}" class="btn btn-primary">
                    <em class="ion-plus"></em> {{'New committee'}}
                </a>
{{--                TODO: make modal(?) to export the member committee member changes since a date (for GMM's)--}}
                <a href="{{route("beheer.committees.exportMemberChanges")}}" class="btn btn-primary">
                    <em class="ion-arrow-down-outline"></em> {{'Export member changes'}}
                </a>
            </div>
        </div>
    </div>
    
    <table id="committeeIndex" class="table table-striped dt-responsive nowrap" style="width: 100%">
        <thead>
        <tr>
            <td>{{"Name"}}</td>
            <td>{{"Abbreviation"}}</td>
            <td>{{"Number of members"}}</td>
            <td>{{"Management"}}</td>
        </tr>
        </thead>
        <tbody>
        @foreach($committees as $committee)
            <tr>
                <td>
                    {{$committee->name}}
                </td>
                <td>
                    {{$committee->abbreviation}}
                </td>
                <td>
                    {{$committee->members()->count()}}
                </td>
                <td>
                    <a class="mr-1" href="{{route("beheer.committees.edit", [$committee])}}"><em class="ion-edit"></em></a>
                    <a class="mr-1" href="{{route("beheer.committees.show", [$committee])}}"><em class="ion-eye"></em></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    @yield('modal_javascript')
    <script>
        $(document).ready(function () {
            $('#committeeIndex').DataTable({
                columnDefs: [
                    {width: '10%', target: 3},
                ]
            });
        });
        console.log({{$committee->chairPersons()}})
    </script>
@endpush

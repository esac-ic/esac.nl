@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ $curPageName }}</h2>
                {!! $content !!}
            </div>
        </div>
    </div>

    <section class="py-3">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <table id="dataTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{'Name'}}</th>
                                <th>{{'Email address'}}</th>
                                <th>{{'Phone number'}}</th>
                                <th>{{'Type of member'}}</th>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->getName()}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phonenumber}}</td>
                                <td>{{trans('kind_of_member.' . $user->kind_of_member)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}"></script>
<script>
    $('#dataTable').DataTable();
</script>
@endpush

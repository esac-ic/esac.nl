@extends('layouts.beheer')

@section('title')
{{'Subscriptions' . ": " . $users["agendaItemTitle"]}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Subscriptions' . ": " . $users["agendaItemTitle"]}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('forms/admin/' . $agendaItemId)}}" class="btn btn-primary">
                    <em class="ion-person-add"></em> {{'New subscription'}}
                </a>
                <a href="{{url('forms/users/'.$users["agendaItemId"].'/export')}}" class="btn btn-primary">
                    <em class="ion-android-download"></em> {{'Export'}}
                </a>
                <a href="{{url('/agendaItems/')}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Email address'}}</th>
            <th>{{"Adres"}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users["userdata"] as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->getAddress()}}</td>

                <td>
                    <a href="{{url('/forms/users/'. $user->id . '/detail/'. $agendaItemId)}}"><span title="{{'Show entered information'}}" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" id="delete_button" data-url="{{url('/forms/'.$users["agendaItemId"].'/remove/'.$user["_signupId"])}}"><span  class="ion-trash-a"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        $(document).on('click','#delete_button',function(){
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                method: 'DELETE',
                contentType: 'application/json',
                success: function(result) {
                    window.location.reload();
                },
                error: function(request,msg,error) {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

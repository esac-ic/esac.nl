@extends('layouts.beheer')

@section('title')
{{trans("forms.Inschrijvingen") . ": " . $users["agendaitem"]}}
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
            <h1>{{trans("forms.Inschrijvingen") . ": " . $users["agendaitem"]}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('forms/admin/' . $agendaId)}}" class="btn btn-primary">
                    <em class="ion-person-add"></em> {{trans("forms.nieuweinschrijving")}}
                </a>
                <a href="{{url('forms/users/'.$users["agendaId"].'/export')}}" class="btn btn-primary">
                    <em class="ion-android-download"></em> {{trans("forms.export")}}
                </a>
                <a href="{{url('/agendaItems/')}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('user.name')}}</th>
            <th>{{trans('user.email')}}</th>
            <th>{{"Adres"}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users["userdata"] as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->getAdress()}}</td>

                <td>
                    <a href="{{url('/forms/users/'. $user->id . '/detail/'. $agendaId)}}"><span title="{{trans("inschrijven.applicationFormDetail")}}" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" id="delete_button" data-url="{{url('/forms/'.$users["agendaId"].'/remove/'.$user["_signupId"])}}"><span  class="ion-trash-a"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script type="text/javascript">
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
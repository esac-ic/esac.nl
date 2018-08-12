@extends('layouts.beheer')

@section('title')
{{$mailList->name}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$mailList->name}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/mailList/'.$mailList->address . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="#" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <em class="ion-plus"></em> {{trans("MailList.addMember")}}
                </a>
                <a href="{{url('/mailList/')}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em>  {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'mailList/' . $mailList->address, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('MailList.mailList')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped dt-responsive nowrap">
                <tr>
                    <td>
                        {{trans('MailList.name')}}
                    </td>
                    <td>
                        {{$mailList->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('MailList.address')}}
                    </td>
                    <td>
                        {{$mailList->address}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('MailList.acces_level')}}
                    </td>
                    <td>
                        {{$mailList->acces_level}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('MailList.description')}}
                    </td>
                    <td>
                        {{$mailList->description}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('MailList.members_count')}}
                    </td>
                    <td>
                        {{$mailList->members_count}}
                    </td>
                </tr>
            </table>
            <h4> {{trans('MailList.members')}}</h4>
            <table id="maillistMembers" class="table table-striped dt-responsive nowrap">
                <thead>
                    <td><strong>{{trans('MailList.address')}}</strong></td>
                    <td><strong>{{trans('MailList.name')}}</strong></td>
                    <td><strong>{{trans('menu.beheer')}}</strong></td>
                </thead>
                <tbody>
                @foreach($mailList->members as $member)
                    <tr>
                        <td>{{$member->address}}</td>
                        <td>{{$member->name}}</td>
                        <td>
                            <a href="#" id="deleteMemebr" data-mailList-id="{{$mailList->address}}" data-member-id="{{$member->address}}"><em class="ion-trash-a"></em></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('beheer.mailList.adduser')
@endsection
@push('styles')
    <style>
        .marginbox {
            margin: 20px;
        }
    </style>
@endpush
@push('scripts')
    @yield('modal_javascript')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#maillistMembers').dataTable({
                @if(App::isLocale('nl'))
                    "language": {
                        "url": "js/dutch.lang"
                }
                @endif
            });
        });

        $(document).on('click','#addMeber',function(){
           console.log('add member');
        });
        $(document).on('click','#deleteMemebr',function(){
           if(confirm('{{trans('menu.confirmDelete')}}')){
               //delete user from maillist
               var mailListId = $(this).attr('data-mailList-id');
               var memberId = $(this).attr('data-member-id');
               $.ajax({
                   url: '/mailList/' + mailListId + '/member/' + memberId,
                   data : {
                       _token : window.Laravel.csrfToken
                   },
                   type: 'DELETE',
                   success : function(){
                       window.location.reload();
                   }
               });
           }

        });
    </script>
@endpush
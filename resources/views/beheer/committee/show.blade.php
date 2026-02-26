@extends('layouts.beheer')

@section('title')
{{$committee->name}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$committee->name}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="#" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addMemberModal">
                    <em class="ion-plus"></em> {{'Add members'}}
                </a>
                <a href="{{route('beheer.committees.index')}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em>  {{'Back'}}
                </a>
                <form method="post" action="{{ route('beheer.committees.destroy', $committee->id) }}" onsubmit="return confirm('Are you sure you want to delete the committee?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-striped dt-responsive nowrap border">
                <tr>
                    <td>
                        {{'Name'}}
                    </td>
                    <td>
                        {{$committee->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Abbreviation'}}
                    </td>
                    <td>
                        {{$committee->abbreviation}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Email'}}
                    </td>
                    <td>
                        {{$committee->email}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Member count'}}
                    </td>
                    <td>
                        {{$committee->getMemberCount()}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Chairperson'}}
                    </td>
                    <td class="">
                        {{$committee->chair->getName()}}
                        <a class="ml-2"><em class="ion-edit"></em></a>
                    </td>
                </tr>
                
            </table>
            
            <h4>{{'Description preview:'}}</h4>
            <p class="border p-2 preserve-line-breaks">{{ $committee->description }}</p>
            
            <h4 class="mt-4"> {{'Members'}}</h4>
            <table id="committeeMembers" class="table table-striped dt-responsive nowrap">
                <thead>
                    <td><strong>{{'Name'}}</strong></td>
                    <td><strong>{{'Email'}}</strong></td>
                    <td><strong>{{'Management'}}</strong></td>
                </thead>
                <tbody>
                @foreach($committee->members as $member)
                    <tr>
                        <td>{{$member->getName()}}</td>
                        <td>{{$member->email}}</td>
                        <td>
                            <a href="#" id="removeMember" data-member-id="{{$member->id}}"><em class="ion-trash-a"></em></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('beheer.committee.addMemberModal')
@endsection
@push('styles')
    <style>
        .marginbox {
            margin: 20px;
        }
        .preserve-line-breaks {
            white-space: pre-wrap;
        }
    </style>
@endpush
@push('scripts')
    @yield('modal_javascript')
    <script>
        $(document).ready(function() {
            $('#committeeMembers').dataTable();
        });
        
        $(document).on('click', '#removeMember', function() {
            const memberId = $(this).attr('data-member-id');
            $.ajax({
                url: '/beheer/committees/' + '{{$committee->id}}' + '/removeMember/' + memberId, //can't just use route('beheer.committees.removeMember', $committee, $member) because the member id is not know when loading the page
                data: {
                    _token: window.Laravel.csrfToken
                },
                type: 'DELETE',
                success: function() {
                    console.log('success');
                    window.location.reload();
                },
                error: function() {
                    console.log('error');
                    console.log('memberId: ' + memberId);
                    console.log('/beheer/committees/' + '{{$committee->id}}' + '/removeMember/' + memberId);
                    
                    window.location.reload();
                }
            });
        });
    </script>
@endpush

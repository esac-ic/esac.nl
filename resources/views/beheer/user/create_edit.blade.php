@extends('layouts.beheer')
@use('Illuminate\Support\Carbon')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @session('message')
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $value }}
        </div>
    @endsession

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif

    <h1>{{$fields['title']}}</h1>

    {{ html()->form($fields['method'], $fields['url'])->open() }}
    <div class="card mt-4" id="personal-info">
        <div class="card-header">
            <h3>{{'Personal'}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-5">
                    {{ html()->label('First name', 'firstname') }}
                    @can('manage-members')
                        {{ html()->text('firstname', ($user != null) ? $user->firstname : "")->class('form-control')->required() }}
                    @else
                        {{ html()->text('firstname', ($user != null) ? $user->firstname : "")->class('form-control')->required()->disabled() }}
                    @endcan
                </div>
                <div class="form-group col-md-2">
                    {{ html()->label('Preposition', 'preposition') }}
                    @can('manage-members')
                        {{ html()->text('preposition', ($user != null) ? $user->preposition : "")->class('form-control') }}
                    @else
                        {{ html()->text('preposition', ($user != null) ? $user->preposition : "")->class('form-control')->disabled() }}
                    @endcan
                </div>
                <div class="form-group col-md-5">
                    {{ html()->label('Last name', 'lastname') }}
                    @can('manage-members')
                        {{ html()->text('lastname', ($user != null) ? $user->lastname : "")->class('form-control')->required() }}
                    @else
                        {{ html()->text('lastname', ($user != null) ? $user->lastname : "")->class('form-control')->required()->disabled() }}
                    @endcan
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Email address', 'email') }}
                    {{ html()->text('email', ($user != null) ? $user->email : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Password', 'password') }}
                    {{ html()->password('password')->class('form-control')->if($fields['method'] === "POST", fn ($input) => $input->required()->minlength(8)) }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Street', 'street') }}
                    {{ html()->text('street', ($user != null) ? $user->street : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('House number', 'houseNumber') }}
                    {{ html()->text('houseNumber', ($user != null) ? $user->houseNumber : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {{ html()->label('Postal code', 'zipcode') }}
                    {{ html()->text('zipcode', ($user != null) ? $user->zipcode : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('City', 'city') }}
                    {{ html()->text('city', ($user != null) ? $user->city : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Country', 'country') }}
                    {{ html()->select('country', trans("countries"), ($user != null) ? $user->country : "NL")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Phone number', 'phonenumber') }}
                    {{ html()->text('phonenumber', ($user != null) ? $user->phonenumber : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Alternative phone number', 'phonenumber_alt') }}
                    {{ html()->text('phonenumber_alt', ($user != null) ? $user->phonenumber_alt : "")->class('form-control') }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Birthdate', 'birthDay') }}
                    <div class="input-group date" id="birthDayBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="birthDay" name="birthDay" data-target="#birthDayBox" value="{{($user != null) ? Carbon::parse($user->birthDay)->format('d-m-Y') : now()->format('d-m-Y')}}" required="required">
                        <div class="input-group-append" data-target="#birthDayBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Type of member', 'kind_of_member') }}
                    @can('manage-members')
                        {{ html()->select('kind_of_member', trans('kind_of_member'), ($user != null) ? $user->kind_of_member : "")->class('form-control')->required()->id('kind_of_member') }}
                    @else
                        {{ html()->select('kind_of_member', trans('kind_of_member'), ($user != null) ? $user->kind_of_member : "")->class('form-control')->required()->id('kind_of_member')->disabled() }}
                    @endcan
                </div>
            </div>
            <div class="form-group">
                {{ html()->label('Remarks', 'remark') }}
                @can('manage-members')
                    {{ html()->text('remark', ($user != null) ? $user->remark : "")->class('form-control') }}
                @else
                    {{ html()->text('remark', ($user != null) ? $user->remark : "")->class('form-control')->disabled() }}
                @endcan
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Financial'}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    {{ html()->label('BIC', 'BIC') }}
                    {{ html()->text('BIC', ($user != null) ? $user->BIC : "")->class('form-control') }}
                </div>
                <div class="form-group col-md-8">
                    {{ html()->label('IBAN', 'IBAN') }}
                    {{ html()->text('IBAN', ($user != null) ? $user->IBAN : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-check">
                {{ html()->checkbox('incasso', ! session()->hasOldInput() && $user != null && $user->incasso === 1)->class('form-check-input')->id('incasso') }}
                {{ html()->label('Accept Automatic Collection', 'incasso')->class('form-check-label') }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Emergency info'}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Emergency address street', 'emergencystreet') }}
                    {{ html()->text('emergencystreet', ($user != null) ? $user->emergencystreet : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Emergency address house number', 'emergencyHouseNumber') }}
                    {{ html()->text('emergencyHouseNumber', ($user != null) ? $user->emergencyHouseNumber : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency postal code', 'emergencyzipcode') }}
                    {{ html()->text('emergencyzipcode', ($user != null) ? $user->emergencyzipcode : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency city', 'emergencycity') }}
                    {{ html()->text('emergencycity', ($user != null) ? $user->emergencycity : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency country', 'emergencycountry') }}
                    {{ html()->select('emergencycountry', trans("countries"), ($user != null) ? $user->emergencycountry : "NL")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-group">
                {{ html()->label('Emergency phone number', 'emergencyNumber') }}
                {{ html()->text('emergencyNumber', ($user != null) ? $user->emergencyNumber : "")->class('form-control')->required() }}
            </div>
        </div>
    </div>
    @can('manage-members')
        <div class="card mt-4">
            <div class="card-header">
                <h3>{{ 'Roles' }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                        @foreach($roles as $role)
                            <div class="form-check">
                                @if($ownedRoles->contains($role->id))
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{$role->id}}" id="{{$role->id}}" checked>
                                @else
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{$role->id}}" id="{{$role->id}}">
                                @endif
                                <label class="form-check-label" for="{{$role->id}}">{{$role->name}}</label>
                            </div>
                        @endforeach
                </div>
            </div>
        </div>
    @endcan
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger" href="{{ $user === null ? route('users.index') : route('users.show', $user) }}">{{'Cancel'}}</a>
    </div>

    {{ html()->form()->close() }}
@endsection

@push('scripts')
    @vite(['resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])
    <script type="module">
        $('#birthDayBox').datetimepicker({
            locale: 'nl',
            format: 'L',
            icons: {
                time: 'ion-clock',
                date: 'ion-calendar',
                up: 'ion-android-arrow-up',
                down: 'ion-android-arrow-down',
                previous: 'ion-chevron-left',
                next: 'ion-chevron-right',
                today: 'ion-calendar',
                clear: 'ion-trash-a',
                close: 'ion-close'
            }
        });
        $(function(){
            $('#birthDayBox').datetimepicker();
        })
        $(function () {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
@endpush

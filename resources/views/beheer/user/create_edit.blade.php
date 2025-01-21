@extends('layouts.beheer')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
@endpush

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif

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
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        {{ html()->text('firstname')->value($user ? $user->firstname : "")->class('form-control')->required() }}
                    @else
                        {{ html()->text('firstname')->value($user ? $user->firstname : "")->class('form-control')->required()->disabled() }}
                    @endif
                </div>
                <div class="form-group col-md-2">
                    {{ html()->label('Preposition', 'preposition') }}
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        {{ html()->text('preposition')->value($user ? $user->preposition : "")->class('form-control') }}
                    @else
                        {{ html()->text('preposition')->value($user ? $user->preposition : "")->class('form-control')->disabled() }}
                    @endif
                </div>
                <div class="form-group col-md-5">
                    {{ html()->label('Last name', 'lastname') }}
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        {{ html()->text('lastname')->value($user ? $user->lastname : "")->class('form-control')->required() }}
                    @else
                        {{ html()->text('lastname')->value($user ? $user->lastname : "")->class('form-control')->required()->disabled() }}
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Email address', 'email') }}
                    {{ html()->text('email')->value($user ? $user->email : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Password', 'password') }}
                    {{ html()->password('password')
                        ->class('form-control')
                        ->attributes($fields['method'] === "POST" ? ['required' => 'required', 'minlength' => 8] : []) }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Street', 'street') }}
                    {{ html()->text('street')->value($user ? $user->street : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('House number', 'houseNumber') }}
                    {{ html()->text('houseNumber')->value($user ? $user->houseNumber : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {{ html()->label('Postal code', 'zipcode') }}
                    {{ html()->text('zipcode')->value($user ? $user->zipcode : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('City', 'city') }}
                    {{ html()->text('city')->value($user ? $user->city : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Country', 'country') }}
                    {{ html()->select('country', trans("countries"))
                        ->value($user ? $user->country : "NL")
                        ->class('form-control')
                        ->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Phone number', 'phonenumber') }}
                    {{ html()->text('phonenumber')->value($user ? $user->phonenumber : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Alternative phone number', 'phonenumber_alt') }}
                    {{ html()->text('phonenumber_alt')->value($user ? $user->phonenumber_alt : "")->class('form-control') }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {{ html()->label('Birthdate', 'birthDay') }}
                    <div class="input-group date" id="birthDayBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="birthDay" name="birthDay" data-target="#birthDayBox" value="{{($user != null) ? \Carbon\Carbon::parse($user->birthDay)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')}}" required="required">
                        <div class="input-group-append" data-target="#birthDayBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Type of member', 'kind_of_member') }}
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        {{ html()->select('kind_of_member', trans('kind_of_member'))
                            ->value($user ? $user->kind_of_member : "")
                            ->class('form-control')
                            ->id('kind_of_member')
                            ->required() }}
                    @else
                        {{ html()->select('kind_of_member', trans('kind_of_member'))
                            ->value($user ? $user->kind_of_member : "")
                            ->class('form-control')
                            ->id('kind_of_member')
                            ->required()
                            ->disabled() }}
                    @endif
                </div>
            </div>
            <div class="form-group">
                {{ html()->label('Remarks', 'remark') }}
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                    {{ html()->text('remark')->value($user ? $user->remark : "")->class('form-control') }}
                @else
                    {{ html()->text('remark')->value($user ? $user->remark : "")->class('form-control')->disabled() }}
                @endif
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
                    {{ html()->text('BIC')->value($user ? $user->BIC : "")->class('form-control') }}
                </div>
                <div class="form-group col-md-8">
                    {{ html()->label('IBAN', 'IBAN') }}
                    {{ html()->text('IBAN')->value($user ? $user->IBAN : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-check">
                {{ html()->checkbox('incasso')
                    ->checked($user ? $user->incasso === 1 : false)
                    ->class('form-check-input')
                    ->id('incasso') }}
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
                    {{ html()->text('emergencystreet')->value($user ? $user->emergencystreet : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-6">
                    {{ html()->label('Emergency address house number', 'emergencyHouseNumber') }}
                    {{ html()->text('emergencyHouseNumber')->value($user ? $user->emergencyHouseNumber : "")->class('form-control')->required() }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency postal code', 'emergencyzipcode') }}
                    {{ html()->text('emergencyzipcode')->value($user ? $user->emergencyzipcode : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency city', 'emergencycity') }}
                    {{ html()->text('emergencycity')->value($user ? $user->emergencycity : "")->class('form-control')->required() }}
                </div>
                <div class="form-group col-md-4">
                    {{ html()->label('Emergency country', 'emergencycountry') }}
                    {{ html()->select('emergencycountry', trans("countries"))
                        ->value($user ? $user->emergencycountry : "NL")
                        ->class('form-control')
                        ->required() }}
                </div>
            </div>
            <div class="form-group">
                {{ html()->label('Emergency phone number', 'emergencyNumber') }}
                {{ html()->text('emergencyNumber')->value($user ? $user->emergencyNumber : "")->class('form-control')->required() }}
            </div>
        </div>
    </div>
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
        <div class="card mt-4">
            <div class="card-header">
                <h3>{{ 'Roles' }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    @foreach($roles as $role)
                        <div class="form-check">
                            {{ html()->checkbox('roles[]')
                                ->value($role->id)
                                ->checked($ownedRoles->contains($role->id))
                                ->class('form-check-input')
                                ->id($role->id) }}
                            {{ html()->label($role->name, $role->id)->class('form-check-label') }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger" href="{{ ($user == null) ? ('/users') : ('/users/' . $user->id)}}">{{'Cancel'}}</a>
    </div>

    {{ html()->form()->close() }}
@endsection

@push('scripts')
    <script src="{{mix("js/vendor/moment.js")}}"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}"></script>
    <script>
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

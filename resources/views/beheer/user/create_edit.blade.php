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

    {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
    <div class="card mt-4" id="personal-info">
        <div class="card-header">
            <h3>{{trans('user.personal')}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-5">
                    {!! Form::label('firstname', trans('user.firstname')) !!}
                    {!! Form::text('firstname', ($user != null) ? $user->firstname : "", ['class' => 'form-control','required' => 'required']) !!}                    </div>
                <div class="form-group col-md-2">
                    {!! Form::label('preposition', trans('user.preposition')) !!}
                    {!! Form::text('preposition', ($user != null) ? $user->preposition : "", ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-5">
                    {!! Form::label('lastname', trans('user.lastname')) !!}
                    {!! Form::text('lastname', ($user != null) ? $user->lastname : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('emaillbl', trans('user.email')) !!}
                    {!! Form::text('email', ($user != null) ? $user->email : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('password', trans('user.password')) !!}
                    {!! Form::password('password', $fields['method'] === "POST" ? array('class'=>'form-control','required' => 'required', 'minlength' => 8): array('class'=>'form-control')) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('street', trans('user.street')) !!}
                    {!! Form::text('street', ($user != null) ? $user->street : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('houseNumber', trans('user.housenumber')) !!}
                    {!! Form::text('houseNumber', ($user != null) ? $user->houseNumber : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('zipcode', trans('user.zipcode')) !!}
                    {!! Form::text('zipcode', ($user != null) ? $user->zipcode : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('city', trans('user.city')) !!}
                    {!! Form::text('city', ($user != null) ? $user->city : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('country', trans('user.country')) !!}
                    {!! Form::select('country',trans("countries"), ($user != null) ? $user->country : "NL", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('phonenumber', trans('user.phonenumber')) !!}
                    {!! Form::text('phonenumber', ($user != null) ? $user->phonenumber : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('phonenumber_alt', trans('user.phonenumber_alt')) !!}
                    {!! Form::text('phonenumber_alt', ($user != null) ? $user->phonenumber_alt : "", ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('birthDay', trans('user.birthDay')) !!}
                    <div class="input-group date" id="birthDayBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="birthDay" name="birthDay" data-target="#birthDayBox" value="{{($user != null) ? \Carbon\Carbon::parse($user->birthDay)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')}}" required="required">
                        <div class="input-group-append" data-target="#birthDayBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('gender', trans('user.gender')) !!}
                    {!! Form::select('gender',["man" => trans('user.man'), "female" => trans('user.female')], ($user != null) ? $user->gender : "", ['class' => 'form-control','required' => 'required','id' => 'gender']) !!}
                </div>
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                    <div class="form-group col-md-4">
                        {!! Form::label('kind_of_member', trans('user.kind_of_member')) !!}
                        {!! Form::select('kind_of_member',trans('kind_of_member'), ($user != null) ? $user->kind_of_member : "", ['class' => 'form-control','required' => 'required','id' => 'kind_of_member']) !!}
                    </div>
                @endif
            </div>
            <div class="form-group">
                {{Form::label('remark',  trans('user.remark'))}}
                {{Form::textarea('remark',($user != null) ? $user->remark : "",array('class' => 'form-control'))}}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('user.financial')}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('BIC', trans('user.BIC')) !!}
                    {!! Form::text('BIC', ($user != null) ? $user->BIC : "", ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-8">
                    {!! Form::label('IBAN', trans('user.IBAN')) !!}
                    {!! Form::text('IBAN', ($user != null) ? $user->IBAN : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-check">
                {!! Form::checkbox("incasso",true,($user != null)? $user->incasso === 1 : false,["class" => "form-check-input", "id" => "incasso"]) !!}
                {!! Form::label("incasso", trans("user.incasso"), ["class" => "form-check-label"]) !!}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('user.emergencyInfo')}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('emergencystreet', trans('user.emergencystreet')) !!}
                    {!! Form::text('emergencystreet', ($user != null) ? $user->emergencystreet : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('emergencyHouseNumber', trans('user.emergencyHouseNumber')) !!}
                    {!! Form::text('emergencyHouseNumber', ($user != null) ? $user->emergencyHouseNumber : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('emergencyzipcode', trans('user.emergencyzipcode')) !!}
                    {!! Form::text('emergencyzipcode', ($user != null) ? $user->emergencyzipcode : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('emergencycity', trans('user.emergencycity')) !!}
                    {!! Form::text('emergencycity', ($user != null) ? $user->emergencycity : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('emergencycountry', trans('user.emergencycountry')) !!}
                    {!! Form::select('emergencycountry',trans("countries"), ($user != null) ? $user->emergencycountry : "NL", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('emergencyNumber', trans('user.emergencyNumber')) !!}
                {!! Form::text('emergencyNumber', ($user != null) ? $user->emergencyNumber : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{ trans('user.rols') }}</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
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
                @endif
            </div>
        </div>
    </div>
    @if(null !== $user && null !== $user->registrationInfo)
        @php($registrationInfo = $user->registrationInfo)
        <div class="card mt-4">
            <div class="card-header">
                <h3>{{trans('user.introPackageOptions')}}</h3>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {!! Form::label('intro_package', trans('user.introPackage')) !!}
                        {!! Form::checkbox('intro_package',1, $registrationInfo->intro_package, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('toprope_course', trans('user.topropCourse')) !!}
                        {!! Form::checkbox('toprope_course',1, $registrationInfo->toprope_course, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {!! Form::label('shirt_size', trans('user.tshirt')) !!}
                        {!! Form::select('shirt_size',trans('user.shirtSizes'), $registrationInfo->shirt_size, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('intro_weekend_date', trans('user.introWeekend')) !!}
                        {!! Form::select('intro_weekend_date',trans('user.weekendDates'), $registrationInfo->intro_weekend_date != null ? $registrationInfo->intro_weekend_date->format('Y-m-d') : null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        <a class="btn btn-danger" href="{{ ($user == null) ? ('/users') : ('/users/' . $user->id)}}">{{trans('menu.cancel')}}</a>
    </div>

    {!! Form::close() !!}
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

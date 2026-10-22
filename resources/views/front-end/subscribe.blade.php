@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
@endpush

@section('title')
{{$curPageName}}
@endsection

@section('content')
<x-page-intro :title="$curPageName" :$content />
<div class="container">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li class="ml-2">{{ $error}}</li>
            @endforeach
        </ul>
    @endif

    {{ html()->form('POST', route('front-end.signup.store'))->open() }}
    <x-card title="Personal" class="mt-4" id="personal-info">
        <div class="form-row">
            <x-field name="firstname" label="First name" col="5" required />
            <x-field name="preposition" label="Preposition" col="2" />
            <x-field name="lastname" label="Last name" col="5" required />
        </div>
        <div class="form-row">
            <x-field name="email" label="Email address" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="password" label="Password" type="password" col="6" required minlength="8" />
            <x-field name="password_confirmation" label="Confirm password" type="password" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="street" label="Street" col="6" required />
            <x-field name="house_number" label="House number" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="zipcode" label="Postal code" col="4" required />
            <x-field name="city" label="City" col="4" required />
            <x-field name="country" label="Country" :options="trans('countries')" value="NL" col="4" required />
        </div>
        <div class="form-row">
            <x-field name="phone_number" label="Phone number" col="6" required />
            <div class="form-group col-md-6">
                <x-date-picker name="birthday" id="birthDay" label="Birthdate" value="" />
            </div>
        </div>
    </x-card>
    <x-card title="Financial" class="mt-4">
        <div class="form-row">
            <x-field name="bic" label="BIC" col="4" />
            <x-field name="iban" label="IBAN" col="8" required />
        </div>
    </x-card>
    <x-card title="Emergency info" class="mt-4">
        <div class="form-row">
            <x-field name="emergency_street" label="Emergency address street" col="6" required />
            <x-field name="emergency_house_number" label="Emergency address house number" col="6" required />
        </div>
        <div class="form-row">
            <x-field name="emergency_zipcode" label="Emergency zip code" col="4" required />
            <x-field name="emergency_city" label="Emergency city" col="4" required />
            <x-field name="emergency_country" label="Emergency country" :options="trans('countries')" value="NL" col="4" required />
        </div>
        <x-field name="emergency_number" label="Emergency phone number" required />
    </x-card>

    <x-card title="Privacy Policy and Automatic Collection" class="mt-4">
        <div class="accordion" id="accordion-1" data-children=".accordion-item">
            <div class="accordion-item">
                <a data-toggle="collapse" data-parent="#accordion-1" href="#accordion-panel-1" aria-expanded="false" aria-controls="accordion-1">
                    <h5>Terms and Conditions</h5>
                    <i class="h5 ion-chevron-right"></i>
                </a>
                <div id="accordion-panel-1" class="collapse" role="tabpanel">
                    <p>By signing this form, you subscribe to the ESAC up until termination of your membership. Without notice, your membership of the ESAC is continued annually. Termination of membership should happen before the 1st of January by sending a letter to the address mentioned above or by sending an email to secretaris@esac.nl.</p>
                    <p>The annual contribution will be taken from your bank account via direct debit. The amount of the contribution is determined by the general members’ assembly (GMA / ALV). If you subscribe on or after the 1st of August, you have to pay a reduced contribution (of which the amount is also determined by the GMA) for the rest of that calendar year.</p>
                    <p>Being a student and being in the possession of a sports card of the Student Sports Centre Eindhoven are mandatory for all new members of the ESAC.</p>
                    <p>If you participate in mountain sports activities, having an insurance which covers (extreme) mountain sports is mandatory. NKBV membership and insurance are strongly recommended. Other insurances are also possible, but you should thoroughly check the policy. You can’t hold the ESAC liable for any possible damage or injuries.</p>
                </div>
            </div>
            <div class="accordion-item">
                <a data-toggle="collapse" data-parent="#accordion-1" href="#accordion-panel-2" aria-expanded="false" aria-controls="accordion-1">
                    <h5>Privacy Policy</h5>
                    <i class="h5 ion-chevron-right"></i>
                </a>
                <div id="accordion-panel-2" class="collapse" role="tabpanel">
                    @include('includes.privacyPolicy')
                </div>
            </div>
            <div class="accordion-item">
                <a data-toggle="collapse" data-parent="#accordion-1" href="#accordion-panel-3" aria-expanded="false" aria-controls="accordion-1">
                <h5>Agreement Direct Debit SEPA</h5>
                <i class="h5 ion-chevron-right"></i>
                </a>
                <div id="accordion-panel-3" class="collapse" role="tabpanel">
                    <p>
                    Collector: Eindhovense Studenten Alpen Club<br>
                    Collector ID: NL75ZZZ402360760000</span>
                    </p>
                    <p>
                        The ESAC has a direct debit agreement with Rabobank. This way, the ESAC and its members can easily handle payments of contribution and other activities, such as climbing weekends. Cash payments or manual transfers won’t be necessary, since the amount can now be automatically cashed with your written or verbal permission. The rules are listed below. These primarily contain the rights of the payee and the obligations for the ESAC.
                    </p>
                    <ul>
                        <li>You can always retain your money without giving a reason if you do not agree with the deduction from your bank account. You have 56 days (8 weeks) to order your bank office to refund the money.</li>
                        <li>The treasurer will announce the collection at least two weeks and up to two months before the money is deducted from your account.</li>
                        <li>The ESAC may only deduct an amount other than the annual subscription from your account after you have given oral or written permission.</li>
                        <li>No money will be deducted if the funds in your account are not sufficient.</li>
                        <li>If you want to terminate the contract, report this to the treasurer by sending a letter to the address included at the top of the page or by sending an email to penningmeester@esac.nl.</li>
                    </ul>
                    <p>By signing this form you authorise your bank to debit your account in accordance with the instructions from the ESAC. As is part of your rights, you are entitled to a refund from your bank under the terms and conditions with your bank. A refund must be claimed within 8 weeks starting from the date your account was debited.</p>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                {{ html()->checkbox('privacy_policy')->class('form-check-input')->id('privacy_policy') }}
                {{ html()->label('Accept the Privacy Policy', 'privacy_policy')->class('form-check-label') }}
            </div>
            <div class="form-check">
                {{ html()->checkbox('incasso')->class('form-check-input')->id('incasso') }}
                {{ html()->label('Accept Automatic Collection', 'incasso')->class('form-check-label') }}
            </div>
            <div class="form-check">
                {{ html()->checkbox('termsconditions')->class('form-check-input')->id('termsconditions') }}
                {{ html()->label('Accept the Terms and Conditions', 'termsconditions')->class('form-check-label') }}
            </div>
            <div class="g-recaptcha" data-sitekey="{{config('services.recaptcha.key')}}"></div>
        </div>
    </x-card>
    <div class="my-4">
        {{ html()->submit('Submit')->class('btn btn-primary') }}
    </div>
    {{ html()->form()->close() }}
</div>
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush

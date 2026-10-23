@extends('layouts.app')

@section('content')
<x-page-intro :title="$pageTitle" :$content />
<div class="container">
    <x-validation-errors />

    {{ html()->form('POST', route('sign-up.store'))->open() }}
    <x-member-fields :$user sign-up />

    <x-card title="Privacy Policy and direct debit" class="mt-4">
        <div class="accordion">
            <details class="accordion-item" name="sign-up-terms">
                <summary>
                    <h5>Terms and Conditions</h5>
                    <i class="h5 ion-chevron-right"></i>
                </summary>
                <p>By signing this form, you subscribe to the ESAC up until termination of your membership. Without notice, your membership of the ESAC is continued annually. Termination of membership should happen before the 1st of January by sending a letter to the address mentioned above or by sending an email to secretaris@esac.nl.</p>
                <p>The annual contribution will be taken from your bank account via direct debit. The amount of the contribution is determined by the general members’ assembly (GMA / ALV). If you subscribe on or after the 1st of August, you have to pay a reduced contribution (of which the amount is also determined by the GMA) for the rest of that calendar year.</p>
                <p>Being a student and being in the possession of a sports card of the Student Sports Centre Eindhoven are mandatory for all new members of the ESAC.</p>
                <p>If you participate in mountain sports activities, having an insurance which covers (extreme) mountain sports is mandatory. NKBV membership and insurance are strongly recommended. Other insurances are also possible, but you should thoroughly check the policy. You can’t hold the ESAC liable for any possible damage or injuries.</p>
            </details>
            <details class="accordion-item" name="sign-up-terms">
                <summary>
                    <h5>Privacy Policy</h5>
                    <i class="h5 ion-chevron-right"></i>
                </summary>
                @include('includes.privacy-policy')
            </details>
            <details class="accordion-item" name="sign-up-terms">
                <summary>
                    <h5>Agreement Direct Debit SEPA</h5>
                    <i class="h5 ion-chevron-right"></i>
                </summary>
                <p>
                Collector: Eindhovense Studenten Alpen Club<br>
                Collector ID: NL75ZZZ402360760000
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
            </details>
        </div>

        <div class="form-group">
            <div class="form-check">
                {{ html()->checkbox('terms_and_conditions')->class(['form-check-input', 'is-invalid' => $errors->has('terms_and_conditions')])->id('terms_and_conditions') }}
                {{ html()->label('Accept the Terms and Conditions', 'terms_and_conditions')->class('form-check-label') }}
            </div>
            <div class="form-check">
                {{ html()->checkbox('privacy_policy')->class(['form-check-input', 'is-invalid' => $errors->has('privacy_policy')])->id('privacy_policy') }}
                {{ html()->label('Accept the Privacy Policy', 'privacy_policy')->class('form-check-label') }}
            </div>
            <div class="form-check">
                {{ html()->checkbox('direct_debit')->class(['form-check-input', 'is-invalid' => $errors->has('direct_debit')])->id('direct_debit') }}
                {{ html()->label('Accept direct debit', 'direct_debit')->class('form-check-label') }}
            </div>
            <div class="g-recaptcha" data-sitekey="{{config('services.recaptcha.key')}}"></div>
        </div>
    </x-card>
    <div class="my-4">
        {{ html()->submit('Sign up')->class('btn btn-primary btn-lg') }}
    </div>
    {{ html()->form()->close() }}
</div>
@endsection

@push('scripts')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush

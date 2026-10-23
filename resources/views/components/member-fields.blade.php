@props(['user', 'memberTypes', 'locked' => false])
{{-- A member's details form; locked, the name, member type and remarks can only be read. --}}
<x-card title="Personal" class="mt-4">
    <div class="form-row">
        <x-field name="first_name" label="First name" :value="$user->first_name" col="5" required :disabled="$locked" />
        <x-field name="preposition" label="Preposition" :value="$user->preposition" col="2" :disabled="$locked" />
        <x-field name="last_name" label="Last name" :value="$user->last_name" col="5" required :disabled="$locked" />
    </div>
    <div class="form-row">
        <x-field name="email" label="Email address" :value="$user->email" col="6" required />
    </div>
    <div class="form-row">
        <x-field name="password" label="Password" type="password" col="6" :required="! $user->exists" :minlength="$user->exists ? null : 8" />
        <x-field name="password_confirmation" label="Confirm password" type="password" col="6" :required="! $user->exists" />
    </div>
    <div class="form-row">
        <x-field name="street" label="Street" :value="$user->street" col="6" required />
        <x-field name="house_number" label="House number" :value="$user->house_number" col="6" required />
    </div>
    <div class="form-row">
        <x-field name="zipcode" label="Postal code" :value="$user->zipcode" col="4" required />
        <x-field name="city" label="City" :value="$user->city" col="4" required />
        <x-field name="country" label="Country" :options="$countries" :value="$user->country ?? 'NL'" col="4" required />
    </div>
    <div class="form-row">
        <x-field name="phone_number" label="Phone number" :value="$user->phone_number" col="6" required />
        <x-field name="phone_number_alt" label="Alternative phone number" :value="$user->phone_number_alt" col="6" />
    </div>
    <div class="form-row">
        <x-field type="date" name="birthday" label="Birthdate" :value="$user->birthday?->toDateString()" col="6" required />
        <x-field name="member_type" label="Member type" :options="$memberTypes" :value="$user->member_type" col="6" required :disabled="$locked" />
    </div>
    <x-field name="remark" label="Remarks" :value="$user->remark" :disabled="$locked" />
</x-card>
<x-card title="Financial" class="mt-4">
    <div class="form-row">
        <x-field name="bic" label="BIC" :value="$user->bic" col="4" />
        <x-field name="iban" label="IBAN" :value="$user->iban" col="8" required />
    </div>
    <div class="form-check">
        {{ html()->checkbox('direct_debit', ! session()->hasOldInput() && $user->direct_debit)->class('form-check-input')->id('direct_debit') }}
        {{ html()->label('Accept direct debit', 'direct_debit')->class('form-check-label') }}
    </div>
</x-card>
<x-card title="Emergency info" class="mt-4">
    <div class="form-row">
        <x-field name="emergency_street" label="Emergency address street" :value="$user->emergency_street" col="6" required />
        <x-field name="emergency_house_number" label="Emergency address house number" :value="$user->emergency_house_number" col="6" required />
    </div>
    <div class="form-row">
        <x-field name="emergency_zipcode" label="Emergency postal code" :value="$user->emergency_zipcode" col="4" required />
        <x-field name="emergency_city" label="Emergency city" :value="$user->emergency_city" col="4" required />
        <x-field name="emergency_country" label="Emergency country" :options="$countries" :value="$user->emergency_country ?? 'NL'" col="4" required />
    </div>
    <x-field name="emergency_number" label="Emergency phone number" :value="$user->emergency_number" required />
</x-card>

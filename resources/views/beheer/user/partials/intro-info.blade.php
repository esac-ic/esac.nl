<table class="table table-striped" style="width:100%">
    <tr>
        <td>{{ trans('user.introPackage') }}</td>
        <td>{{ trans('user.packageTypes.' . $user->registrationInfo->package_type) }}</td>
    </tr>
    <tr>
        <td>{{ trans('user.tshirt') }}</td>
        <td>{{ trans('user.shirtSizes.' . $user->registrationInfo->shirt_size) }}</td>
    </tr>
    <tr>
        <td>{{ trans('user.introWeekend') }}</td>
        <td>{{ trans('user.weekendDates.' . $user->registrationInfo->intro_weekend) }}</td>
    </tr>
</table>
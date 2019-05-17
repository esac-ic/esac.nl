<table class="table table-striped" style="width:100%">
    <tr>
        <td>{{ trans('user.introPackage') }}</td>
        <td>{{ $user->registrationInfo->intro_package ? trans('menu.yes') : trans('menu.no') }}</td>
    </tr>
    <tr>
        <td>{{ trans('user.topropCourse') }}</td>
        <td>{{ $user->registrationInfo->toprope_course ? trans('menu.yes') : trans('menu.no') }}</td>
    </tr>
    <tr>
        <td>{{ trans('user.tshirt') }}</td>
        <td>{{ trans('user.shirtSizes')[$user->registrationInfo->shirt_size] }}</td>
    </tr>
    <tr>
        <td>{{ trans('user.introWeekend') }}</td>
        <td>{{ $user->registrationInfo->intro_weekend_date != null ? $user->registrationInfo->intro_weekend_date->format('d-m-Y') : ""  }}</td>
    </tr>
</table>
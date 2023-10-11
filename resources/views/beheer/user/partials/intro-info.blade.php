<table class="table table-striped" style="width:100%">
    <tr>
        <td>{{ 'Intro pakket' }}</td>
        <td>{{ trans('user.packageTypes.' . $user->registrationInfo->package_type) }}</td>
    </tr>
    <tr>
        <td>{{ 'ESAC Intro shirt' }}</td>
        <td>{{ trans('user.shirtSizes.' . $user->registrationInfo->shirt_size) }}</td>
    </tr>
    <tr>
        <td>{{ 'Intro weekend' }}</td>
        <td>{{ trans('user.weekendDates.' . $user->registrationInfo->intro_weekend) }}</td>
    </tr>
</table>
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;

class RegistrationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function registrationsByUser(Request $request)
    {
        $user = User::query()
            ->with([
                'applicationResponses',
                'applicationResponses.agendaItem',
            ])
            ->findOrFail($request->input('user_id'));

        Gate::authorize('update', $user);

        $registrations = [];
        foreach ($user->applicationResponses as $applicationRespons) {
            array_push($registrations, [
                'name' => $applicationRespons->agendaItem->title,
                'startDate' => Carbon::parse($applicationRespons->agendaItem->startDate)->format('d-m-Y H:i'),
                'actions' => '
                    <a href="' . route('forms.users.show', [$user, $applicationRespons->agenda_id]) . '"><span title="' . 'Show entered information' . '" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" id="delete_button" data-url="' . route('forms.destroy', [$applicationRespons->agenda_id, $applicationRespons]) . '"><span  class="ion-trash-a"></span></a>
                ',
            ]);
        }

        return response()
            ->json($registrations);
    }
}

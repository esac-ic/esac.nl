<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

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
        $userId = $request->input('user_id', -1);

        if (!Auth::user()->hasRole(Role::Administrator->value)) {
            if (Auth::user()->id != $userId) {
                abort(401);
            }
        }

        $user = User::query()
            ->with([
                'applicationResponses',
                'applicationResponses.agendaItem',
            ])
            ->find($userId);

        $registrations = [];
        if ($user != null) {
            foreach ($user->applicationResponses as $applicationRespons) {
                array_push($registrations, [
                    'name' => $applicationRespons->agendaItem->title,
                    'startDate' => Carbon::parse($applicationRespons->agendaItem->startDate)->format('d-m-Y H:i'),
                    'actions' => '
                        <a href="' . url('/forms/users/' . $user->id . '/detail/' . $applicationRespons->agenda_id) . '"><span title="' . 'Show entered information' . '" class="ion-eye" aria-hidden="true"></span></a>
                        <a href="#" id="delete_button" data-url="' . url('/forms/' . $applicationRespons->agenda_id . '/remove/' . $applicationRespons->id) . '"><span  class="ion-trash-a"></span></a>
                    ',
                ]);
            }
        }

        return response()
            ->json($registrations);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function registrationsByUser(Request $request)
    {
        $userId = $request->get('user_id', -1);

        if (!Auth::user()->hasRole(Config::get('constants.Administrator'))) {
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

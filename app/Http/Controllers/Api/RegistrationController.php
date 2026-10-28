<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegistrationResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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

    public function registrationsByUser(Request $request): JsonResponse
    {
        $user = User::query()
            ->with([
                'applicationResponses',
                'applicationResponses.agendaItem',
            ])
            ->findOrFail($request->input('user_id'));

        Gate::authorize('update', $user);

        return response()->json(RegistrationResource::collection($user->applicationResponses));
    }
}

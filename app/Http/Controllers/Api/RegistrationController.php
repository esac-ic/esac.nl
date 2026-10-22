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

    public function index(Request $request): JsonResponse
    {
        $request->validate(['user_id' => ['required', 'integer']]);

        $user = User::query()->with('registrations.event')->findOrFail($request->integer('user_id'));

        Gate::authorize('view', $user);

        return response()->json(RegistrationResource::collection($user->registrations));
    }
}

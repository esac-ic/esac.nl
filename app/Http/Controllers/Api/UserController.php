<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-mail-lists', only: ['index']),
            new Middleware('can:manage-news', only: ['autoComplete']),
        ];
    }

    public function index(): array
    {
        return ['aaData' => MemberResource::collection(User::query()->current()->get(['id', 'email', 'firstname', 'preposition', 'lastname']))];
    }

    public function autoComplete(Request $request): JsonResponse
    {
        $term = '%'.addcslashes((string) $request->input('term'), '%_\\').'%';

        $users = User::query()->current()
            ->whereAny(['firstname', 'lastname'], 'like', $term)
            ->get(['firstname', 'lastname'])
            ->map(fn (User $user) => ['text' => $user->firstname.' '.$user->lastname]);

        return response()->json($users);
    }
}

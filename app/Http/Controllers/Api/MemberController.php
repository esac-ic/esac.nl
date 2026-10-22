<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-mail-lists', only: ['index']),
            new Middleware('can:manage-news', only: ['autocomplete']),
        ];
    }

    public function index(): array
    {
        return ['aaData' => MemberResource::collection(User::query()->active()->get(['id', 'email', 'first_name', 'preposition', 'last_name']))];
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $term = '%'.addcslashes((string) $request->input('term'), '%_\\').'%';

        $users = User::query()->active()
            ->whereAny(['first_name', 'last_name'], 'like', $term)
            ->get(['first_name', 'last_name'])
            ->map(fn (User $user) => ['text' => $user->first_name.' '.$user->last_name]);

        return response()->json($users);
    }
}

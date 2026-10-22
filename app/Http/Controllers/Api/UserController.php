<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function autoComplete(Request $request): JsonResponse
    {
        $users = User::query()
            ->where(function (Builder $query) use ($request) {
                $term = $request->input('term', '');
                $query->where('firstname', 'like', "%$term%")
                    ->orWhere('lastname', 'like', "%$term%");
            })
            ->selectRaw("CONCAT(firstname, ' ', lastname) as text")
            ->get();

        return response()
            ->json($users);
    }
}

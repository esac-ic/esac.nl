<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function autoComplete(Request $request): JsonResponse
    {
        $users = User::query()
            ->where(function (Builder $query) use ($request) {
                $term = $request->get('term', '');
                $query->where('firstname', 'like', "%$term%")
                    ->orWhere('lastname', 'like', "%$term%");
            })
            ->selectRaw("CONCAT(firstname, ' ', lastname) as text")
            ->get();

        return response()
            ->json($users);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                $query->where('firstname', $term)
                    ->orWhere('lastname', $term);
            })
            ->selectRaw("CONCAT(firstname, ' ', lastname) as text")
            ->get();

        return response()
            ->json($users);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\OldUsersExport;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OldMemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-members'),
        ];
    }

    public function index(): View
    {
        return view('beheer.user.index_old_users', [
            'users' => User::query()->old()->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']),
        ]);
    }

    public function export(OldUsersExport $oldUsersExport): StreamedResponse
    {
        return $oldUsersExport->download('Old members.xlsx');
    }
}

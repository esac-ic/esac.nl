<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FormerMembersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormerMemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-members'),
        ];
    }

    public function index(): View
    {
        return view('admin.former-members.index', [
            'users' => User::query()->former()->get(['id', 'first_name', 'last_name', 'email', 'preposition', 'member_type']),
        ]);
    }

    public function export(FormerMembersExport $formerMembersExport): StreamedResponse
    {
        return $formerMembersExport->download('Former members.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberExportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-members'),
        ];
    }

    public function __invoke(UsersExport $usersExport): StreamedResponse
    {
        return $usersExport->download('Members.xlsx');
    }
}

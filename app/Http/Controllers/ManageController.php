<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class ManageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(): View
    {
        if (Auth::guest() || !Auth::user()->hasBackendRights()) {
            abort(403, 'You do not have sufficient access to view this page');
        }

        return view('beheer/home');
    }
}

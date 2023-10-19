<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ManageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::guest() || !Auth::user()->hasBackendRigths()) {
            abort(403, 'You do not have sufficient access to view this page');
        }

        return view('beheer/home');
    }
}

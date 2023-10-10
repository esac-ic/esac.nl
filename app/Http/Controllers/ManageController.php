<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ManageController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        if(Auth::guest() || !Auth::user()->hasBackendRigths()){
            abort(403, trans('validation.Unauthorized'));
        }

        return view('beheer/home');
    }
}

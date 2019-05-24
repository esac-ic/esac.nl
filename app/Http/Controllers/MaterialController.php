<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\Mail\MaterialNotification;


class MaterialController extends Controller
{
    /**
     * MaterialController constructor.
     */
    public function __construct()
    {

    }

    public function toMail(Request $request)
    {
	    \Mail::to('ttcommandeur@gmail.com')->send(new MaterialNotification($request->input()));

	    return redirect('home');

    }
 }

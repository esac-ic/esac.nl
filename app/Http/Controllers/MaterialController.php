<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\Mail\MaterialNotification;
use App\Materiaal;


class MaterialController extends Controller
{
    /**
     * MaterialController constructor.
     */
    private $_materiaalRepository;

    public function __construct(RepositorieFactory $repositorieFactory)
    {

    }

    public function index()
    {
        $materialen = App\Materiaal::all();
        return view('', compact('materialen'));
    }

    public function toMail(Request $request)
    {
	    \Mail::to('@gmail.com')->send(new MaterialNotification($request->input()));

	    return redirect('home');

    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'type' => 'required',
            'season' => 'required'
        ]);
    }
 }

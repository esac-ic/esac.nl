<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class SettingsController extends Controller
{
    private $_LANGSESSIONNAME = "lang";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('setLanguage');;
        $this->middleware('authorize:' . Config::get('constants.Administrator'))
            ->except('setLanguage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();
        return view('beheer.settings.index',compact('settings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $settings = Setting::all();
        return view('beheer.settings.edit',compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $settings = Setting::all();
        foreach ($request->get('setting',[]) as  $key => $value){
            $setting = $settings->where('name','=',$key)->first();
            $setting->value = $value;
            $setting->save();
        }

        Session::flash("message",trans('settings.flashUpdateSetting'));
        return redirect('/beheer/settings');
    }

    public function setLanguage(Request $request){
        $language = Input::get('language','nl');
        $request->session()->put($this->_LANGSESSIONNAME,$language);
        return "";
    }
}

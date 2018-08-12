<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailgunFacade;
use App\MailList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class MailListController extends Controller
{

    private $_mailgunFacade;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MailgunFacade $mailgunFacade)
    {
        $this->_mailgunFacade =  $mailgunFacade;
        $this->middleware('auth');
        $this->middleware('authorize:'.Config::get('constants.Administrator'));
    }

    //gives the mail list views
    public function index(){
        $mailLists = $this->_mailgunFacade->getAllMailLists();

        return view('beheer.mailList.index', compact('mailLists'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => trans('MailList.add'),
            'method' => 'POST',
            'url' => '/mailList',];
        $mailList = null;
        return view('beheer.mailList.create_edit', compact('fields','mailList'));
    }

    //store maillist
    public function store(Request $request){
        $mailist = new MailList($request->all());
        $this->_mailgunFacade->storeMailList($mailist);

        Session::flash("message",trans('MailList.added'));
        return redirect('/mailList');
    }

    public function show(Request $request, $mailList){
        $mailList = $this->_mailgunFacade->getMailList($mailList);
        return view('beheer.mailList.show', compact('mailList'));

    }

    //show edit screen
    public function edit(Request $request, $mailList){
        $fields = ['title' => trans('MailList.edit'),
            'method' => 'PATCH',
            'url' => '/mailList/' . $mailList,];
        $mailList = $this->_mailgunFacade->getMailList($mailList);
        return view('beheer.mailList.create_edit', compact('fields','mailList'));
    }

    //update maillist
    public function update(Request $request){
        $mailist = new MailList($request->all());
        $this->_mailgunFacade->updateMailList($request['id'],$mailist);

        Session::flash("message",trans('MailList.edited'));
        return redirect('/mailList');
    }

    public function destroy(Request $request, $mailList){
        $this->_mailgunFacade->deleteMailList($mailList);

        return redirect('/mailList');
    }

    public function addMember(Request $request, $mailList){
        $this->_mailgunFacade->addMember($mailList,$request['email'],$request['name']);
        return "";
    }

    public function deleteMeberOfMailList($mailList,$member){
        $this->_mailgunFacade->deleteMemberFromMailList($mailList,$member);
        return;
    }

}

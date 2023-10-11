<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class MailListController extends Controller
{

    private $_mailListFacade;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MailListFacade $mailListFacade)
    {
        $this->_mailListFacade = $mailListFacade;
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    //gives the mail list views
    public function index()
    {
        $mailLists = $this->_mailListFacade->getAllMailLists();

        return view('beheer.mailList.index', compact('mailLists'));
    }

    //show create screen
    public function create()
    {
        $fields = ['title' => 'Add mailing list',
            'method' => 'POST',
            'url' => '/mailList'];
        $mailList = null;
        return view('beheer.mailList.create_edit', compact('fields', 'mailList'));
    }

    //store maillist
    public function store(Request $request)
    {
        $this->_mailListFacade->storeMailList($request->all());

        Session::flash("message", 'Mailing list added');
        return redirect('/mailList');
    }

    public function show(Request $request, $mailList)
    {
        $mailList = $this->_mailListFacade->getMailList($mailList);
        return view('beheer.mailList.show', compact('mailList'));

    }

    public function destroy(Request $request, $mailList)
    {
        $this->_mailListFacade->deleteMailList($mailList);

        return redirect('/mailList');
    }

    public function addMember(Request $request, $mailList)
    {
        $users = User::find($request->get('userIds'));
        foreach ($users as $user) {
            $this->_mailListFacade->addMember($mailList, $user->email, $user->getName());
        }

        return "";
    }

    public function deleteMeberOfMailList($mailList, $member)
    {
        $this->_mailListFacade->deleteMemberFromMailList($mailList, $member);
        return;
    }

}

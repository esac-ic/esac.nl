<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberMassMailListSync;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MailListController extends Controller
{

    private MailListFacade $_mailListFacade;
    private UserRepository $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MailListFacade $mailListFacade, UserRepository $userRepository)
    {
        $this->_mailListFacade = $mailListFacade;
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
        $this->userRepository = $userRepository;
    }

    //gives the mail list views
    public function index()
    {
        $mailLists = $this->_mailListFacade->getAllMailLists()->all();

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
        try {
            $this->_mailListFacade->storeMailList($request->all());
            Session::flash("message", 'Mailing list added');
        } catch (RequestException $e) {
            $code = $e->getCode();
            $body = $e->getMessage() ?? 'An unknown error occurred';
            Session::flash("error", $msg = "Error $code: $body");
            Log::error($msg, ['exception' => $e]);
        }

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

    public function deleteMemberFromMailList($mailList, $member)
    {
        $this->_mailListFacade->deleteMemberFromMailList($mailList, $member);
    }

    
    public function massMemberMailListSync()
    {
        $users = $this->userRepository->getCurrentUsers(); //should give user objects
        MemberMassMailListSync::dispatch($users); //add all these users to the maillists corresponding to their member types.
        return redirect('/mailList');
    }
}

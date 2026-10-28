<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\Jobs\MemberMassMailListSyncJob;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MailListController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly MailListFacade $_mailListFacade, private readonly UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    //gives the mail list views
    public function index(): View
    {
        $mailLists = $this->_mailListFacade->getAllMailLists()->all();

        return view('beheer.mailList.index', compact('mailLists'));
    }

    //show create screen
    public function create(): View
    {
        $fields = ['title' => 'Add mailing list',
            'method' => 'POST',
            'url' => '/mailList'];
        $mailList = null;
        return view('beheer.mailList.create_edit', compact('fields', 'mailList'));
    }

    //store maillist
    public function store(Request $request): RedirectResponse
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

    public function show(Request $request, $mailList): View
    {
        $mailList = $this->_mailListFacade->getMailList($mailList);
        return view('beheer.mailList.show', compact('mailList'));

    }

    public function destroy(Request $request, $mailList): RedirectResponse
    {
        $this->_mailListFacade->deleteMailList($mailList);

        return redirect('/mailList');
    }

    public function addMember(Request $request, $mailList): string
    {
        $users = User::find($request->input('userIds'));
        foreach ($users as $user) {
            $this->_mailListFacade->addMember($mailList, $user->email, $user->getName());
        }

        return "";
    }

    public function deleteMemberFromMailList($mailList, $member): void
    {
        $this->_mailListFacade->deleteMemberFromMailList($mailList, $member);
    }

    
    public function massMemberMailListSync(): RedirectResponse
    {
        // One job per member, so a long list doesn't hit the worker's timeout and each member is retried on its own
        $this->userRepository->getCurrentUsers()->each(fn (User $user) => MemberMassMailListSyncJob::dispatch($user));
        return redirect('/mailList');
    }
}

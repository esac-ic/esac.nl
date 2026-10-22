<?php

namespace App\Http\Controllers;

use App\Services\Mailman;
use App\Jobs\MemberMassMailListSyncJob;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MailListController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-mail-lists'),
        ];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly Mailman $mailman)
    {
    }

    //gives the mail list views
    public function index(): View
    {
        $mailLists = $this->mailman->getAllMailLists()->all();

        return view('beheer.mailList.index', compact('mailLists'));
    }

    //show create screen
    public function create(): View
    {
        $fields = ['title' => 'Add mailing list',
            'method' => 'POST',
            'url' => route('mailList.store')];
        $mailList = null;
        return view('beheer.mailList.create_edit', compact('fields', 'mailList'));
    }

    //store maillist
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->mailman->storeMailList($request->all());
            Session::flash("message", 'Mailing list added');
        } catch (RequestException $e) {
            $code = $e->getCode();
            $body = $e->getMessage() ?? 'An unknown error occurred';
            Session::flash("error", $msg = "Error $code: $body");
            Log::error($msg, ['exception' => $e]);
        }

        return to_route('mailList.index');
    }

    public function show(Request $request, $mailList): View
    {
        $mailList = $this->mailman->getMailList($mailList);
        return view('beheer.mailList.show', compact('mailList'));
    }

    public function destroy(Request $request, $mailList): RedirectResponse
    {
        $this->mailman->deleteMailList($mailList);

        return to_route('mailList.index');
    }

    public function addMember(Request $request, $mailList): string
    {
        $users = User::find($request->input('userIds'));
        foreach ($users as $user) {
            $this->mailman->addMember($mailList, $user->email, $user->getName());
        }

        return "";
    }

    public function deleteMemberFromMailList($mailList, $member): void
    {
        $this->mailman->deleteMemberFromMailList($mailList, $member);
    }

    
    public function massMemberMailListSync(): RedirectResponse
    {
        $users = User::current()->get(); //should give user objects
        
        MemberMassMailListSyncJob::dispatch($users); //add all these users to the maillists corresponding to their member types.
        return to_route('mailList.index');
    }
}

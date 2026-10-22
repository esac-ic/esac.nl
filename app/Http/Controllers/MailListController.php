<?php

namespace App\Http\Controllers;

use App\Jobs\MemberMassMailListSyncJob;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MailListController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-mail-lists'),
        ];
    }

    public function __construct(private readonly Mailman $mailman) {}

    public function index(): View
    {
        return view('beheer.mailList.index', ['mailLists' => $this->mailman->getAllMailLists()->all()]);
    }

    public function create(): View
    {
        return view('beheer.mailList.create_edit', [
            'fields' => [
                'title' => 'Add mailing list',
                'method' => 'POST',
                'url' => route('mailList.store'),
            ],
            'mailList' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['address' => ['required', 'alpha_dash', 'max:255']]);

        try {
            $this->mailman->storeMailList($data);
        } catch (RequestException $e) {
            $message = "Error {$e->getCode()}: {$e->getMessage()}";
            Log::error($message, ['exception' => $e]);

            return to_route('mailList.index')->with('error', $message);
        }

        return to_route('mailList.index')->with('message', 'Mailing list added');
    }

    public function show(string $mailList): View
    {
        return view('beheer.mailList.show', ['mailList' => $this->mailman->getMailList($mailList)]);
    }

    public function destroy(string $mailList): RedirectResponse
    {
        $this->mailman->deleteMailList($mailList);

        return to_route('mailList.index');
    }

    public function addMember(Request $request, string $mailList): Response
    {
        $request->validate([
            'userIds' => ['required', 'array'],
            'userIds.*' => ['integer', Rule::exists('users', 'id')],
        ]);

        foreach (User::query()->findMany($request->input('userIds')) as $user) {
            $this->mailman->addMember($mailList, $user->email, $user->getName());
        }

        return response()->noContent();
    }

    public function deleteMemberFromMailList(string $mailList, string $member): Response
    {
        $this->mailman->deleteMemberFromMailList($mailList, $member);

        return response()->noContent();
    }

    public function massMemberMailListSync(): RedirectResponse
    {
        // One job per member, so a long list doesn't hit the worker's timeout and each member is retried on its own
        User::current()->get()->each(fn (User $user) => MemberMassMailListSyncJob::dispatch($user));
        return to_route('mailList.index');
    }
}

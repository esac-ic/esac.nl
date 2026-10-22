<?php

namespace App\Http\Controllers;

use App\Jobs\MemberMassMailListSyncJob;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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
        return view('admin.mail-lists.index', ['mailLists' => $this->mailman->getAllMailLists()->all()]);
    }

    public function create(): View
    {
        return view('admin.mail-lists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['address' => ['required', 'alpha_dash', 'max:255']]);

        $this->mailman->storeMailList($data['address']);

        return to_route('admin.mail-lists.index')->with('message', 'Mailing list added');
    }

    public function show(string $mailList): View
    {
        return view('admin.mail-lists.show', ['mailList' => $this->mailman->getMailList($mailList)]);
    }

    public function destroy(string $mailList): RedirectResponse
    {
        $this->mailman->deleteMailList($mailList);

        return to_route('admin.mail-lists.index');
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
        MemberMassMailListSyncJob::dispatch();

        return to_route('admin.mail-lists.index');
    }
}

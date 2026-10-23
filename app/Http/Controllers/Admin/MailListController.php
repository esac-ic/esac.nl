<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AddMemberToMailLists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MailListController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-mail-lists'),
        ];
    }

    public function __construct(private readonly Mailman $mailman) {}

    public function index(): View
    {
        return view('admin.mail-lists.index', ['mailLists' => $this->mailman->mailLists()->all()]);
    }

    public function create(): View
    {
        return view('admin.mail-lists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['address' => ['required', 'alpha_dash', 'max:255']]);

        $this->mailman->createMailList($data['address']);

        return to_route('admin.mail-lists.index')->with('message', 'Mail list added');
    }

    public function show(string $mailList): View
    {
        return view('admin.mail-lists.show', [
            'mailList' => $this->mailman->mailList($mailList),
            'members' => User::query()->active()->orderBy('first_name')->get(['id', 'email', 'first_name', 'preposition', 'last_name']),
        ]);
    }

    public function destroy(string $mailList): RedirectResponse
    {
        $this->mailman->deleteMailList($mailList);

        return to_route('admin.mail-lists.index');
    }

    /**
     * Adds every current member to the mail lists of their member type, one queued job per member,
     * so a long list doesn't hit the worker's timeout and each member is retried on its own.
     */
    public function sync(): RedirectResponse
    {
        User::query()->active()->get()->each(fn (User $user) => AddMemberToMailLists::dispatch($user));

        return to_route('admin.mail-lists.index');
    }
}

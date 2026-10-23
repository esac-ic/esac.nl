<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

/**
 * Adds members to a mail list or removes one, by their email address.
 */
class MailListMemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-mail-lists'),
        ];
    }

    public function __construct(private readonly Mailman $mailman) {}

    public function store(Request $request, string $mailList): Response
    {
        $request->validate([
            'member_ids' => ['required', 'array'],
            'member_ids.*' => ['integer', Rule::exists('users', 'id')],
        ]);

        foreach (User::query()->findMany($request->input('member_ids')) as $member) {
            $this->mailman->addMember($mailList, $member->email, $member->name);
        }

        return response()->noContent();
    }

    public function destroy(string $mailList, string $member): RedirectResponse
    {
        $this->mailman->removeMember($mailList, $member);

        return back();
    }
}

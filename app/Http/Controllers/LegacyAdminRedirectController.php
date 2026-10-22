<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Permanently redirects the old /beheer admin URLs, which bookmarks and links may still use, to /admin.
 */
class LegacyAdminRedirectController extends Controller
{
    /**
     * The renamed admin pages, by the start of their old path.
     */
    private const array RENAMED = [
        'users/old_members' => 'former-members',
        'users/pending_members' => 'pending-members',
        'users/exportUsers' => 'members/export',
        'users/pennoMemberExportView' => 'penno-export/create',
        'users' => 'members',
        'rols' => 'roles',
        'agendaItems' => 'events',
        'agendaItemCategories' => 'event-categories',
        'applicationForms' => 'registration-forms',
        'newsItems' => 'news-items',
        'mailList' => 'mail-lists',
        'userEventLog' => 'member-log',
    ];

    public function __invoke(Request $request, string $path = 'home'): RedirectResponse
    {
        foreach (self::RENAMED as $old => $new) {
            if ($path === $old || str_starts_with($path, "$old/")) {
                $path = $new.Str::after($path, $old);
                break;
            }
        }

        $query = $request->getQueryString();

        return redirect('/admin/'.$path.($query === null ? '' : "?$query"), 301);
    }
}

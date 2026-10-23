<?php

namespace App\Http\Controllers;

use App\Enums\PageSlug;
use App\Models\Book;
use App\Models\Event;
use App\Models\NewsItem;
use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function events(): View
    {
        return view('site.events.index', [
            'pageTitle' => 'Events',
            'content' => Page::contentFor(PageSlug::Events),
        ]);
    }

    public function showEvent(Event $event): View
    {
        return view('site.events.show', [
            'event' => $event,
            'registrations' => $event->registrations()->with('user.certificates')->get(),
            'pageTitle' => $event->title,
        ]);
    }

    public function zekeringen(): View
    {
        return view('site.zekeringen', [
            'pageTitle' => 'Zekeringen',
            'content' => Page::contentFor(PageSlug::Zekeringen),
        ]);
    }

    public function news(): View
    {
        return view('site.news.index', [
            'pageTitle' => 'News',
            'content' => Page::contentFor(PageSlug::News),
            'newsItems' => NewsItem::query()->latest('id')->paginate(9),
        ]);
    }

    public function showNewsItem(NewsItem $newsItem): View
    {
        return view('site.news.show', [
            'newsItem' => $newsItem,
            'pageTitle' => $newsItem->title,
        ]);
    }

    public function library(): View
    {
        return view('site.library', [
            'pageTitle' => 'Library',
            'content' => Page::contentFor(PageSlug::Library),
            'books' => Book::all(),
        ]);
    }

    public function members(): View
    {
        return view('site.members', [
            'users' => User::query()->active()->get(['first_name', 'last_name', 'email', 'preposition', 'member_type', 'phone_number']),
            'pageTitle' => 'Members list',
            'content' => Page::contentFor(PageSlug::Members),
        ]);
    }

    public function home(): View
    {
        return view('site.home', [
            'newsItems' => NewsItem::query()->latest('id')->take(3)->get(),
            'content' => Page::contentFor(PageSlug::Home),
            'pageTitle' => 'Home',
            'events' => Event::query()->upcoming()->withCount('registrations')->take(4)->get(),
        ]);
    }

    public function showPage(Page $page): View
    {
        // Sends a guest to the login page, and back here afterwards, when the page is for members only.
        if ($page->requires_login && auth()->guest()) {
            throw new AuthenticationException;
        }

        return view('site.page', [
            'content' => $page->content,
            'pageTitle' => $page->name,
        ]);
    }
}

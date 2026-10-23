<?php

namespace App\Http\Controllers;

use App\Enums\PageSlug;
use App\Models\Book;
use App\Models\Event;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function events(): View
    {
        return view('site.events.index', [
            'pageTitle' => 'Events',
            'content' => $this->pageContent(PageSlug::Events),
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
        $menuItem = MenuItem::query()->where('slug', PageSlug::Zekeringen)->firstOrFail();

        $this->requireLoginFor($menuItem);

        return view('site.zekeringen', [
            'pageTitle' => 'Zekeringen',
            'content' => $menuItem->content,
        ]);
    }

    public function news(): View
    {
        return view('site.news.index', [
            'pageTitle' => 'News',
            'content' => $this->pageContent(PageSlug::News),
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
            'content' => $this->pageContent(PageSlug::Library),
            'books' => Book::all(),
        ]);
    }

    public function members(): View
    {
        $menuItem = MenuItem::query()->where('slug', PageSlug::Members)->firstOrFail();

        $this->requireLoginFor($menuItem);

        return view('site.members', [
            'users' => User::query()->active()->get(['first_name', 'last_name', 'email', 'preposition', 'member_type', 'phone_number']),
            'pageTitle' => 'Members list',
            'content' => $menuItem->content,
        ]);
    }

    public function home(): View
    {
        return view('site.home', [
            'newsItems' => NewsItem::query()->latest('id')->take(3)->get(),
            'content' => $this->pageContent(PageSlug::Home),
            'pageTitle' => 'Home',
            'events' => Event::query()->upcoming()->withCount('registrations')->take(4)->get(),
        ]);
    }

    public function showPage(MenuItem $menuItem): View
    {
        $this->requireLoginFor($menuItem);

        return view('site.page', [
            'content' => $menuItem->content,
            'pageTitle' => $menuItem->name,
        ]);
    }

    private function pageContent(PageSlug $slug): ?string
    {
        return MenuItem::query()->where('slug', $slug)->value('content');
    }

    /**
     * Sends a guest to the login page, and back here afterwards, when the page is for members only.
     */
    private function requireLoginFor(MenuItem $page): void
    {
        if ($page->requires_login && auth()->guest()) {
            throw new AuthenticationException;
        }
    }
}

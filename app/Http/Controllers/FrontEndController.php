<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\Book;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class FrontEndController extends Controller
{
    public function agenda(): View
    {
        return view('front-end.agenda', [
            'categories' => AgendaItemCategory::query()->pluck('name', 'id')->all(),
            'curPageName' => 'Events',
            'content' => $this->pageContent(MenuItem::AGENDAURL),
        ]);
    }

    public function agendaDetailView(AgendaItem $agendaItem): View
    {
        return view('front-end.agenda_detail', [
            'agendaItem' => $agendaItem->loadCount('registrations'),
            // Anonymised registrations still count, but have no one to list
            'registrations' => $agendaItem->registrations()->whereNotNull('user_id')->with('user.certificates')->get(),
            'curPageName' => $agendaItem->title,
        ]);
    }

    public function zekeringen(): View|RedirectResponse
    {
        $menuItem = MenuItem::query()->firstWhere('url_name', MenuItem::ZEKERINGURL);

        if (Gate::denies('view', $menuItem)) {
            return to_route('login');
        }

        return view('front-end.zekeringen', [
            'curPageName' => 'Zekeringen',
            'content' => $menuItem->content,
        ]);
    }

    public function publicSubscribe(): View
    {
        return view('front-end.subscribe', [
            'curPageName' => 'Subscribe as member',
            'content' => $this->pageContent(MenuItem::SUBSCRIBEURL),
        ]);
    }

    public function news(): View
    {
        return view('front-end.news', [
            'curPageName' => 'News',
            'content' => $this->pageContent(MenuItem::NEWSURL),
            'newsItems' => NewsItem::query()->latest('id')->paginate(9),
        ]);
    }

    public function newsDetailView(NewsItem $newsItem): View
    {
        return view('front-end.news_detail', [
            'newsItem' => $newsItem,
            'curPageName' => $newsItem->title,
        ]);
    }

    public function library(): View
    {
        return view('front-end.library', [
            'curPageName' => 'Library',
            'content' => $this->pageContent(MenuItem::LIBRARYURL),
            'books' => Book::all(),
        ]);
    }

    public function memberList(): View|RedirectResponse
    {
        $menuItem = MenuItem::query()->firstWhere('url_name', MenuItem::MEMBERLISTURL);

        if (Gate::denies('view', $menuItem)) {
            return to_route('login');
        }

        return view('front-end.memberlist', [
            'users' => User::query()->current()->get(['firstname', 'lastname', 'email', 'preposition', 'kind_of_member', 'phone_number']),
            'curPageName' => 'Members list',
            'content' => $menuItem->content,
        ]);
    }

    public function home(): View
    {
        return view('front-end.home', [
            'newsItems' => NewsItem::query()->latest('id')->take(3)->get(),
            'content' => $this->pageContent(MenuItem::HOMEURL),
            'curPageName' => 'Home',
            'agendaItems' => AgendaItem::query()->upcoming()->withCount('registrations')->take(4)->get(),
        ]);
    }

    public function showPage(MenuItem $menuItem): View|RedirectResponse
    {
        if (Gate::denies('view', $menuItem)) {
            return to_route('login');
        }

        return view('front-end.templade', [
            'content' => $menuItem->content,
            'curPageName' => $menuItem->name,
        ]);
    }

    private function pageContent(string $urlName): ?string
    {
        return MenuItem::query()->firstWhere('url_name', $urlName)->content;
    }
}

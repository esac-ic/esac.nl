<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\Book;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\User;
use App\Services\AgendaApplicationFormService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function agendaDetailView(AgendaItem $agendaItem, AgendaApplicationFormService $agendaApplicationFormService): View
    {
        $users = [];
        if ($agendaItem->application_form_id != null) {
            foreach ($agendaApplicationFormService->getRegisteredUsers($agendaItem)['userdata'] as $user) {
                $users[$user->id] = [
                    'name' => $user->getName(),
                    'certificate_names' => $user->certificate_names,
                ];
            }
        }

        return view('front-end.agenda_detail', [
            'agendaItem' => $agendaItem,
            'users' => $users,
            'curPageName' => $agendaItem->title,
        ]);
    }

    public function zekeringen(): View|RedirectResponse
    {
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::ZEKERINGURL);

        if (! $menuItem->show()) {
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
            'newsItems' => NewsItem::latest('id')->paginate(9),
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
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::MEMBERLISTURL);

        if (! $menuItem->show()) {
            return to_route('login');
        }

        return view('front-end.memberlist', [
            'users' => User::current()->get(['firstname', 'lastname', 'email', 'preposition', 'kind_of_member', 'phone_number']),
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
            'agendaItems' => AgendaItem::upcoming()->withCount('registrations')->take(4)->get(),
        ]);
    }

    public function showPage(MenuItem $menuItem): View|RedirectResponse
    {
        if (! $menuItem->show()) {
            return to_route('login');
        }

        return view('front-end.templade', [
            'content' => $menuItem->content,
            'curPageName' => $menuItem->name,
        ]);
    }

    private function pageContent(string $urlName): ?string
    {
        return MenuItem::firstWhere('url_name', $urlName)->content;
    }
}

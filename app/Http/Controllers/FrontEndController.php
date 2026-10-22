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
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function agenda(): View
    {
        $categories = [];
        $curPageName = 'Events';
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::AGENDAURL);
        $content = $menuItem->content;

        foreach (AgendaItemCategory::all() as $category) {
            $categories[$category->id] = $category->name;
        }

        return view('front-end.agenda', compact('categories', 'curPageName', 'content'));
    }

    public function agendaDetailView(
        AgendaItem $agendaItem,
        AgendaApplicationFormService $agendaApplicationFormService
    ): View {
        $users = [];
        $curPageName = $agendaItem->title;
        if ($agendaItem->application_form_id != null) {
            foreach ($agendaApplicationFormService->getRegisteredUsers($agendaItem)['userdata'] as $user) {
                $users[$user->id] = [
                    "name" => $user->getName(),
                    "certificate_names" => $user->certificate_names,
                ];
            }
        }

        return view("front-end.agenda_detail", compact('agendaItem', 'users', 'curPageName'));
    }

    public function zekeringen()
    {
        $curPageName = 'Zekeringen';
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::ZEKERINGURL);
        $content = $menuItem->content;

        if (!$menuItem->show()) {
            return to_route('login');
        }

        return view("front-end.zekeringen", compact('curPageName', 'content'));
    }

    public function publicSubscribe(): View
    {
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::SUBSCRIBEURL);
        $content = $menuItem->content;
        $curPageName = 'Subscribe as member';
        return view("front-end.subscribe", compact('curPageName', 'content'));
    }

    public function news(): View
    {
        $newsItems = NewsItem::orderBy('id', 'desc')->paginate(9);
        $curPageName = 'News';
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::NEWSURL);
        $content = $menuItem->content;

        return view("front-end.news", compact('curPageName', 'content', 'newsItems'));
    }

    public function newsDetailView(Request $request, NewsItem $newsItem): View
    {
        $curPageName = $newsItem->title;
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::NEWSURL);
        $content = $menuItem->content;

        return view("front-end.news_detail", compact('newsItem', 'curPageName'));
    }

    public function library(): View
    {
        $books = Book::all();
        $curPageName = 'Library';
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::LIBRARYURL);
        $content = $menuItem->content;

        return view("front-end.library", compact('curPageName', 'content', 'books'));
    }

    public function memberlist()
    {
        $users = User::current()->get(['firstname', 'lastname', 'email', 'preposition', 'kind_of_member', 'phone_number']);
        $curPageName = 'Members list';
        $menuItem = MenuItem::firstWhere('url_name', MenuItem::MEMBERLISTURL);
        $content = $menuItem->content;

        if (!$menuItem->show()) {
            return to_route('login');
        }

        return view('front-end.memberlist', compact('users', 'curPageName', 'content'));
    }

    public function home(): View
    {
        $newsItems = NewsItem::query()->latest('id')->take(3)->get();
        $agendaItems = AgendaItem::upcoming()->with('getApplicationFormResponses')->take(4)->get();
        $curPageName = 'Home';
        $menuItem = MenuItem::query()
            ->where('url_name', '=', MenuItem::HOMEURL)
            ->first();
        $content = $menuItem->content;

        return view('front-end.home', compact('newsItems', 'content', 'curPageName', 'agendaItems'));
    }

    //displays the page which is requested.
    public function showPage(Request $request, $menuItemUrl)
    {
        $menuItem = MenuItem::firstWhere('url_name', $menuItemUrl);

        if ($menuItem === null) {
            abort(404);
        }
        if (!$menuItem->show()) {
            return to_route('login');
        }

        $curPageName = $menuItem->name;
        $content = $menuItem->content;
        return view('front-end.templade', compact('content', 'curPageName'));
    }
}

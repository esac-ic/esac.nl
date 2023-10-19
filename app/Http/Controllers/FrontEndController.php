<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\MenuItem;
use App\NewsItem;
use App\Repositories\AgendaItemCategoryRepository;
use App\Repositories\AgendaItemRepository;
use App\Repositories\BookRepository;
use App\Repositories\MenuRepository;
use App\Repositories\NewsItemRepository;
use App\Repositories\UserRepository;
use App\Services\AgendaApplicationFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FrontEndController extends Controller
{
    private $_menuItemRepository;
    private $_agendaCategoryRepository;
    private $_newsItemRepository;
    private $_agendaRepository;
    private $_userRepository;
    private $_bookRepository;

    public function __construct(
        MenuRepository $menuItemRepository,
        AgendaItemCategoryRepository $agendaCategoryRepository,
        NewsItemRepository $newsItemRepository,
        AgendaItemRepository $agendaRepository,
        UserRepository $userRepository,
        BookRepository $bookRepository) {
        $this->_menuItemRepository = $menuItemRepository;
        $this->_agendaCategoryRepository = $agendaCategoryRepository;
        $this->_newsItemRepository = $newsItemRepository;
        $this->_agendaRepository = $agendaRepository;
        $this->_userRepository = $userRepository;
        $this->_bookRepository = $bookRepository;
    }

    public function agenda()
    {
        $categories = array();
        $curPageName = 'Events';
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::AGENDAURL);
        $content = $menuItem->content;

        foreach ($this->_agendaCategoryRepository->all() as $category) {
            $categories[$category->id] = $category->name;
        }

        return view('front-end.agenda', compact('categories', 'curPageName', 'content'));
    }

    public function agendaDetailView(
        AgendaItem $agendaItem,
        AgendaApplicationFormService $agendaApplicationFormService
    ) {
        $users = array();
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
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::ZEKERINGURL);
        $content = $menuItem->content;

        if (!$menuItem->show()) {
            return Redirect::to('/login');
        }

        return view("front-end.zekeringen", compact('curPageName', 'content'));
    }

    public function publicSubscribe()
    {
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::SUBSCRIBEURL);
        $content = $menuItem->content;
        $curPageName = 'Subscribe as member';
        return view("front-end.subscribe", compact('curPageName', 'content'));
    }

    public function news()
    {
        $newsItems = NewsItem::orderBy('id', 'desc')->paginate(9);
        $curPageName = 'News';
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::NEWSURL);
        $content = $menuItem->content;

        return view("front-end.news", compact('curPageName', 'content', 'newsItems'));
    }

    public function newsDetailView(Request $request, NewsItem $newsItem)
    {
        $curPageName = $newsItem->title;
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::NEWSURL);
        $content = $menuItem->content;

        return view("front-end.news_detail", compact('newsItem', 'curPageName'));
    }

    public function library()
    {
        $books = $this->_bookRepository->all(array("id", "title", "year", "type", "country", "code"));
        $curPageName = 'Library';
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::LIBRARYURL);
        $content = $menuItem->content;

        return view("front-end.library", compact('curPageName', 'content', 'books'));
    }

    public function memberlist()
    {
        $users = $this->_userRepository->getCurrentUsers(array('firstname', 'lastname', 'email', 'preposition', 'kind_of_member', 'phonenumber'));
        $curPageName = 'Members list';
        $menuItem = $this->_menuItemRepository->findby('urlName', MenuItem::MEMBERLISTURL);
        $content = $menuItem->content;

        if (!$menuItem->show()) {
            return Redirect::to('/login');
        }

        return view('front-end.memberlist', compact('users', 'curPageName', 'content'));
    }

    public function home(AgendaItemRepository $agendaItemRepository)
    {
        $newsItems = $this->_newsItemRepository->getLastXNewsItems(3);
        $agendaItems = $this->_agendaRepository->getFirstXItems(4);
        $curPageName = 'Home';
        $menuItem = MenuItem::query()
            ->where('urlName', '=', MenuItem::HOMEURL)
            ->first();
        $content = $menuItem->content;

        return view('front-end.home', compact('newsItems', 'content', 'curPageName', 'agendaItems'));
    }

    //displays the page which is requested.
    public function showPage(Request $request, $menuItemUrl)
    {
        $menuItem = $this->_menuItemRepository->findby('urlName', $menuItemUrl);

        if ($menuItem === null) {
            abort(404);
        }
        if (!$menuItem->show()) {
            return Redirect::to('/login');
        }

        $curPageName = $menuItem->name;
        $content = $menuItem->content;
        return view('front-end.templade', compact('content', 'curPageName'));
    }
}

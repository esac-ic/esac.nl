<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\CustomClasses\MenuSingleton;
use App\MenuItem;
use App\repositories\AgendaItemRepository;
use App\repositories\RepositorieFactory;
use App\Services\AgendaApplicationFormService;
use App\Setting;
use App\Zekering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\NewsItem;
use App\User;
use App\Book;

class frontEndController extends Controller
{
    private $_MenuItemRepository;
    private $_agendaCategoryRepository;
    private $_newsItemRepository;
    private $_agendaRepository;
    private $_userRepository;

    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->_MenuItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$MENUREPOKEY);
        $this->_agendaCategoryRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY);
        $this->_newsItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$NEWSITEMREPOKEY);
        $this->_agendaRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMREPOKEY);
        $this->_userRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
        $this->_bookRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$BOOKREPOKEY);
        $this->_PhotoAlbumRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$PHOTOALBUMREPOKEY);
    }

    public function agenda(){
        $categories = array();
        $curPageName = trans('AgendaItems.agendaItem');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::AGENDAURL);
        $content = $menuItem->content->text();

        foreach ( $this->_agendaCategoryRepository->all() as $category){
            $categories[$category->id] = $category->categorieName->text();
        }

        return view('front-end.agenda', compact('categories','curPageName','content'));
    }

    public function photoAlbums()
    {
        $photoAlbums = $this->_PhotoAlbumRepository->all();
        foreach($photoAlbums as $photoalbum){
              $photoalbum->description = str_replace("\r\n","<br>", $photoalbum->description);
        }
        $curPageName = trans('front-end/photo.pagetitle');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::PHOTOURL);
        $content = $menuItem->content->text();
        return view('front-end.photo_album_list', compact('curPageName', 'photoAlbums', 'content'));
    }

    public function agendaDetailView(
        AgendaItem $agendaItem,
        AgendaApplicationFormService $agendaApplicationFormService
    ) {
        $users = array();
        $curPageName = $agendaItem->agendaItemTitle->text();
        if($agendaItem->application_form_id != null){
            foreach ($agendaApplicationFormService->getRegisteredUsers($agendaItem)['userdata'] as $user){
                $users[$user->id] = [
                    "name" => $user->getName(),
                    "certificate_names" => $user->certificate_names
                ];
            }
        }

        return view("front-end.agenda_detail", compact('agendaItem','users','curPageName'));
    }

    public function zekeringen(){
        $curPageName = trans('front-end/zekeringen.title');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::ZEKERINGURL);
        $content = $menuItem->content->text();

        if(!$menuItem->show()){
            return Redirect::to('/login');
        }

        return view("front-end.zekeringen", compact('curPageName','content'));
    }

    public function publicSubscribe(){
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::SUBSCRIBEURL);
        $content = $menuItem->content->text();
        $curPageName = trans('front-end/subscribe.title');
        $showIntroPackageForm = app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_SHOW_INTRO_OPTION);
        return view("front-end.subscribe", compact('curPageName','content', 'showIntroPackageForm'));
    }

    public function news(){
        $newsItems = NewsItem::orderBy('id', 'desc')->paginate(9);
        $curPageName = trans('front-end/news.title');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::NEWSURL);
        $content = $menuItem->content->text();

        return view("front-end.news", compact('curPageName','content','newsItems'));
    }
    
    public function newsDetailView(Request $request, NewsItem $newsItem){
        $curPageName = $newsItem->newsItemTitle->text();
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::NEWSURL);
        $content = $menuItem->content->text();

        return view("front-end.news_detail", compact('newsItem','curPageName'));
    }

    public function library(){
        $books = $this->_bookRepository->all(array("id","title","year","type","country","code"));
        $curPageName = trans('front-end/library.title');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::LIBRARYURL);
        $content = $menuItem->content->text();

        return view("front-end.library", compact('curPageName','content','books'));
    }
  
    public function memberlist(){
        $users = $this->_userRepository->getCurrentUsers(array('firstname','lastname','email','preposition','kind_of_member','phonenumber'));
        $curPageName = trans('front-end/memberlist.memberlist');
        $menuItem = $this->_MenuItemRepository->findby('urlName',MenuItem::MEMBERLISTURL);
        $content = $menuItem->content->text();

        if(!$menuItem->show()){
            return Redirect::to('/login');
        }

        return view('front-end.memberlist', compact('users','curPageName','content'));
    }

    public function home(AgendaItemRepository $agendaItemRepository){
        $newsItems = $this->_newsItemRepository->getLastXNewsItems(3);
        $agendaItems = $this->_agendaRepository->getFirstXItems(4);
        $curPageName = trans('front-end/home.title');
        $menuItem = MenuItem::query()
            ->with('content')
            ->where('urlName','=',MenuItem::HOMEURL)
            ->first();
        $content = $menuItem->content->text();

        return view('front-end.home', compact('newsItems','content','curPageName','agendaItems'));
    }

    //displays the page which is requested.
    public function showPage(Request $request, $menuItemUrl){
        $menuItem = $this->_MenuItemRepository->findby('urlName',$menuItemUrl);

        if($menuItem === null){
            abort(404);
        }
        if(!$menuItem->show()){
            return Redirect::to('/login');
        }
        
        $curPageName = $menuItem->text->text();
        $content = $menuItem->content->text();
        return view('front-end.templade', compact('content','curPageName'));
    }
}

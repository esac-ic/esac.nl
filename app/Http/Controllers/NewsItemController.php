<?php

namespace App\Http\Controllers;

use App\NewsItem;
use App\Repositories\RepositorieFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManagerStatic as Image;


class NewsItemController extends Controller
{
    private $_newsItemRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.Config::get('constants.Content_administrator') .',' . Config::get('constants.Activity_administrator'));
        $this->_newsItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$NEWSITEMREPOKEY);
    }

    //gives the  news item views
    public function index(){
        $newsItems = $this->_newsItemRepository->all(array("id","title","created_at","author"));
        return view('beheer.newsItem.index', compact('newsItems'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => ('NewsItem.add'),
            'method' => 'POST',
            'url' => '/newsItems',];
        $newsItem = null;
        return view('beheer.newsItem.create_edit', compact('fields','newsItem'));
    }

    //store  news item
    public function store(Request $request){
        $this->validateInput($request);
        $newsItem = $this->_newsItemRepository->create($request->all());

        //save image
        if($request->hasFile('thumbnail')){
            //larger header image for detail view
            $name_header = $newsItem->id . '-header.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('newsItems', $name_header,'public');
            $newsItem->image_url = 'newsItems/' .  $name_header;

            //smaller thumbnail image for list view
            $name_thumbnail = $newsItem->id . '-header.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('newsItems', $name_thumbnail,'public');
            $newsItem->thumbnail_url = 'newsItems/' .  $name_header;
            $newsItem->save();

            //resize both images
            $header_path = storage_path('app/public/' . $newsItem->image_url);
            Image::make($header_path)->fit(1200, 500)->save($header_path);
            $thumb_path = storage_path('app/public/' . $newsItem->thumbnail_url);
            Image::make($thumb_path)->fit(400, 300)->save($thumb_path);
        }

        \Session::flash("message",('NewsItem.added'));
        return redirect('/newsItems');
    }

    public function show(Request $request, NewsItem $newsItem){
        return view('beheer.newsItem.show', compact('newsItem'));
    }

    //show edit screen
    public function edit(Request $request,NewsItem $newsItem){
        $fields = ['title' => ('NewsItem.edit'),
            'method' => 'PATCH',
            'url' => '/newsItems/'. $newsItem->id];

        return view('beheer.newsItem.create_edit', compact('fields','newsItem'));
    }

    //update  news item
    public function update(Request $request,NewsItem $newsItem){
        $this->validateInput($request);
        $this->_newsItemRepository->update($newsItem->id,$request->all());

        //save image
        if($request->hasFile('thumbnail')){
            //larger header image for detail view
            $name_header = $newsItem->id . '-header.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('newsItems', $name_header,'public');
            $newsItem->image_url = 'newsItems/' .  $name_header;

            //smaller thumbnail image for list view
            $name_thumbnail = $newsItem->id . '-thumbnail.' . $request->thumbnail->extension();
            $request->file('thumbnail')->storeAs('newsItems', $name_thumbnail,'public');
            $newsItem->thumbnail_url = 'newsItems/' .  $name_thumbnail;
            $newsItem->save();

            //resize both images
            $header_path = storage_path('app/public/' . $newsItem->image_url);
            Image::make($header_path)->fit(1200, 500)->save($header_path);
            $thumb_path = storage_path('app/public/' . $newsItem->thumbnail_url);
            Image::make($thumb_path)->fit(400, 300)->save($thumb_path);
        }

        \Session::flash("message",('NewsItem.edited'));
        return redirect('/newsItems');
    }

    public function destroy(Request $request, NewsItem $newsItem){
        $this->_newsItemRepository->delete($newsItem->id);

        \Session::flash("message",('NewsItem.deleted'));
        return redirect('/newsItems');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'NL_title' => 'required',
            'EN_title' => 'required',
            'NL_text' => 'required',
            'EN_text' => 'required',
        ]);
    }
}

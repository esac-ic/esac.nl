<?php

namespace App\Http\Controllers;

use App\FrontEndReplacement;
use App\repositories\RepositorieFactory;
use Illuminate\Http\Request;

class FrontEndReplacementController extends Controller
{
    /**
     * AgendaItemController constructor.
     */

    private $_feItemRepository;

    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Activity_administrator'));
        $this->_feItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$FEITEMREPOKEY);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frontEndReplacements = FrontEndReplacement::all();
        return view('beheer.frontEndReplacement.index',compact('frontEndReplacements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields = ['title' => trans('frontEndReplacement.add'),
            'method' => 'POST',
            'url' => '/frontEndReplacement',];
        return view('beheer.frontEndReplacement.create_edit', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->_feItemRepository->create($request->all());
        return $this->index();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FrontEndReplacement::destroy($id);
        return redirect()
            ->back();

    }

    public function getAll() {
        $allitems = FrontEndReplacement::all("word","replacement","email");
        $words = array();
        $emails = array();
        foreach($allitems as $item) {
            if($item['replacement'] != null) {
                $emails[$item['word']] = str_replace('@', 'apestaartje', $item['email']);
                $words[$item['word']] = $item['replacement'];
            } else {
                $words[$item['word']] = $item['replacement'];
            }
        }
        return array($words,$emails);
    }
}

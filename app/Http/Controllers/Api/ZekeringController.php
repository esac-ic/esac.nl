<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RepositorieFactory;
use App\Repositories\ZekeringenRepository;
use App\Zekering;
use Illuminate\Http\Request;

class ZekeringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Activity_administrator'))->only('destroy');
    }

    public function getZekeringen(Request $request, RepositorieFactory $repositorieFactory) {
        $zekeringenRepo = $repositorieFactory->getRepositorie($repositorieFactory::$ZEKERINGENREPOKEY);
        
        $zekeringen = $zekeringenRepo->findBy('has_parent',false)->with('children')->get();
    
        return [
            "zekeringen" => $zekeringen
        ];
    }

    public function storeZekering(Request $request,ZekeringenRepository $zekeringenRepository){
        $this->validateInput($request);
        $zekeringenRepository->create($request->all());

        return redirect('/zekeringen');
    }

    public function storeSubZekering(Request $request,ZekeringenRepository $zekeringenRepository){
        $this->validateInput($request);
        $data = $request->all();
        $data['parent'] = $request->get('parent_id');

        $zekeringenRepository->create($data);

        return response(["msg" => "Sub zekering created"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, RepositorieFactory $repositorieFactory)
    {
        $zekeringenRepo = $repositorieFactory->getRepositorie($repositorieFactory::$ZEKERINGENREPOKEY);
        $zekeringenRepo->delete($id);

        return response("",200);
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'text' => 'required'
        ]);
    }
}

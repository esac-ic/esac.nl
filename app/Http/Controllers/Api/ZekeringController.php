<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ZekeringenRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ZekeringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Activity_administrator'))->only('destroy');
    }

    public function getZekeringen(ZekeringenRepository $zekeringenRepository)
    {
        $zekeringen = $zekeringenRepository->findBy('has_parent', false)->with('children')->get();

        return ['zekeringen' => $zekeringen];
    }

    public function storeZekering(Request $request, ZekeringenRepository $zekeringenRepository)
    {
        $this->validateInput($request);

        $zekeringenRepository->create($request->all());

        return redirect('/zekeringen');
    }

    public function storeSubZekering(Request $request, ZekeringenRepository $zekeringenRepository)
    {
        $this->validateInput($request);

        $data = $request->all();
        $data['parent'] = $request->get('parent_id');

        $zekeringenRepository->create($data);

        return response(["msg" => "Sub zekering created"], 200);
    }

    public function destroy(int $id, ZekeringenRepository $zekeringenRepository)
    {
        $zekeringenRepository->delete($id);

        return response("", 200);
    }

    private function validateInput(Request $request)
    {
        $request->validate($this->getValidationRules());
    }

    private function getValidationRules()
    {
        return ['text' => 'required'];
    }
}

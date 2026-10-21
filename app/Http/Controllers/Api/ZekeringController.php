<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Repositories\ZekeringenRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ZekeringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Role::ActivityAdministrator->value)->only('destroy');
    }

    public function getZekeringen(ZekeringenRepository $zekeringenRepository): array
    {
        $zekeringen = $zekeringenRepository->findBy('has_parent', false)->with('children')->get();

        return ['zekeringen' => $zekeringen];
    }

    public function storeZekering(Request $request, ZekeringenRepository $zekeringenRepository): RedirectResponse
    {
        $this->validateInput($request);

        $zekeringenRepository->create($request->all());

        return redirect('/zekeringen');
    }

    public function storeSubZekering(Request $request, ZekeringenRepository $zekeringenRepository): Response
    {
        $this->validateInput($request);

        $data = $request->all();
        $data['parent'] = $request->input('parent_id');

        $zekeringenRepository->create($data);

        return response(["msg" => "Sub zekering created"], 200);
    }

    public function destroy(int $id, ZekeringenRepository $zekeringenRepository): Response
    {
        $zekeringenRepository->delete($id);

        return response("", 200);
    }

    private function validateInput(Request $request): void
    {
        $request->validate($this->getValidationRules());
    }

    private function getValidationRules(): array
    {
        return ['text' => 'required'];
    }
}

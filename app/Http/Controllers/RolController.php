<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Repositories\RolRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RolController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly RolRepository $_rolRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    //gives the rol views
    public function index(): View
    {
        $rols = $this->_rolRepository->all(['name', 'id']);
        return view('beheer.rol.index', compact('rols'));
    }

    //show create screen
    public function create(): View
    {
        $fields = ['title' => 'Add a role',
            'method' => 'POST',
            'url' => '/rols'];
        $rol = null;
        return view('beheer.rol.create_edit', compact('fields', 'rol'));
    }

    //store rol
    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);

        $this->_rolRepository->create($request->all());

        Session::flash("message", 'Role added');
        return redirect('/rols');
    }

    public function show(Request $request, Rol $rol): View
    {
        return view('beheer.rol.show', compact('rol'));
    }

    //show edit screen
    public function edit(Request $request, Rol $rol): View
    {
        $fields = ['title' => 'Edit role',
            'method' => 'PATCH',
            'url' => '/rols/' . $rol->id];

        return view('beheer.rol.create_edit', compact('fields', 'rol'));
    }

    //update rol
    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $this->validateInput($request);

        $this->_rolRepository->update($rol->id, $request->all());

        Session::flash("message", 'Role edited');
        return redirect('/rols');
    }

    public function destroy(Request $request, Rol $rol): RedirectResponse
    {
        $this->_rolRepository->delete($rol->id);

        Session::flash("message", 'Role removed');
        return redirect('/rols');
    }

    private function validateInput(Request $request): void
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
    }
}

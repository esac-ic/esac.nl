<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Rol;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class RolController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value),
        ];
    }

    //gives the rol views
    public function index(): View
    {
        $rols = Rol::all(['name', 'id']);
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
        Rol::create($this->validateInput($request));

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
        $rol->update($this->validateInput($request));

        Session::flash("message", 'Role edited');
        return redirect('/rols');
    }

    public function destroy(Request $request, Rol $rol): RedirectResponse
    {
        $rol->delete();

        Session::flash("message", 'Role removed');
        return redirect('/rols');
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'name' => 'required|max:255',
        ]);
    }
}

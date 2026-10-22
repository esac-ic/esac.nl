<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Rol;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RolController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-roles'),
        ];
    }

    public function index(): View
    {
        return view('beheer.rol.index', ['rols' => Rol::all(['name', 'id'])]);
    }

    public function create(): View
    {
        return view('beheer.rol.create_edit', ['rol' => new Rol]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        Rol::create($request->validated());

        return to_route('rols.index')->with('message', 'Role added');
    }

    public function show(Rol $rol): View
    {
        return view('beheer.rol.show', ['rol' => $rol]);
    }

    public function edit(Rol $rol): View
    {
        return view('beheer.rol.create_edit', ['rol' => $rol]);
    }

    public function update(RoleRequest $request, Rol $rol): RedirectResponse
    {
        $rol->update($request->validated());

        return to_route('rols.index')->with('message', 'Role edited');
    }

    public function destroy(Rol $rol): RedirectResponse
    {
        $rol->delete();

        return to_route('rols.index')->with('message', 'Role removed');
    }
}

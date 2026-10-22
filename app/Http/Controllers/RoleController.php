<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
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
        return view('admin.roles.index', ['roles' => Role::all(['name', 'id'])]);
    }

    public function create(): View
    {
        return view('admin.roles.create_edit', ['role' => new Role]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        Role::query()->create($request->validated());

        return to_route('admin.roles.index')->with('message', 'Role added');
    }

    public function show(Role $role): View
    {
        return view('admin.roles.show', ['role' => $role]);
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.create_edit', ['role' => $role]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        return to_route('admin.roles.index')->with('message', 'Role edited');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return to_route('admin.roles.index')->with('message', 'Role removed');
    }
}

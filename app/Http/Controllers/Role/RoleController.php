<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('roles.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Roles Page";

        $roles = Role::orderBy('created_at', 'DESC')->get();

        return view('roles.index', compact('roles', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('roles.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Roles Page";

        return view('roles.create', compact('pageTitle'));
    }

    public function store(CreateRoleRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;

            Role::create($data);

            return redirect()->route('roles.index')->with('status', 'Role created successfully.');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function show(Role $role)
    {
        $pageTitle = "Roles Page";

        return view('roles.show', compact('role', 'pageTitle'));
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->can('roles.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Roles Page";

        return view('roles.edit', compact('role', 'pageTitle'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $role->update($request->validated());

            return redirect()->route('roles.index')->with('status', 'Role updated successfully.');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}

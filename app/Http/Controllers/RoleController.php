<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index-roles', compact('roles'));
    }

    public function create()
    {
        return view('roles.create-roles');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'string|max:50',
            'code' => 'string|max:20',
            'description' => 'nullable|string',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role added.');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show-roles', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit-roles', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'role_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}

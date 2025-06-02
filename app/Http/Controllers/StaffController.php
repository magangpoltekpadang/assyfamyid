<?php

namespace App\Http\Controllers;
use App\Models\Staff\Staff;
use App\Models\Outlet\Outlet;
use App\Models\Role\Role;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;


class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::with(['outlet', 'role'])->get();
        return view('staff.index-staff', compact('staffs'));
    }

    public function create()
    {
        $outlets = Outlet::where('is_active', 1)->get();
        $roles = Role::all();
        return view('staff.create-staff', compact('outlets', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'outlet_id' => 'required|exists:outlets,outlet_id',
            'role_id' => 'required|exists:roles,role_id',
            'is_active' => 'required|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
        }
        unset($validated['password']);

        Staff::create($validated);

        return redirect()->route('staff.index')->with('success', 'Staff added.');
    }

    public function show($id)
    {
        $staff = Staff::with(['outlet', 'role'])->findOrFail($id);
        return view('staff.show-staff', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $outlets = Outlet::where('is_active', 1)->get();
        $roles = Role::all();
        return view('staff.edit-staff', compact('staff', 'outlets', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'outlet_id' => 'nullable|exists:outlets,outlet_id',
            'role_id' => 'nullable|exists:roles,role_id',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
        }
        unset($validated['password']);

        $staff->update($validated);

        return redirect()->route('staff.index')->with('success', 'Staff updated.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff deleted.');
    }
}

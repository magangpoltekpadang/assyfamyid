<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet\Outlet;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::all();
        return view('outlets.index-outlets', compact('outlets'));
    }

    public function create()
    {
        return view('outlets.create-outlets');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_name' => 'string|max:100',
            'address' => 'string',
            'phone_number' => 'string|max:20',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        Outlet::create($validated);

        return redirect()->route('outlets.index')->with('success', 'Outlet added successfully.');
    }

    public function show($id)
    {
        $outlet = Outlet::findOrFail($id);
        return view('outlets.show-outlets', compact('outlet'));
    }

    public function edit($id)
    {
        $outlet = Outlet::findOrFail($id);
        return view('outlets.edit-outlets', compact('outlet'));
    }

    public function update(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $validated = $request->validate([
            'outlet_name' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
        ]);

        $outlet->update($validated);

        return redirect()->route('outlets.index')->with('success', 'Outlet updated successfully.');
    }

    public function destroy($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->delete();

        return redirect()->route('outlets.index')->with('success', 'Outlet deleted successfully.');
    }
}

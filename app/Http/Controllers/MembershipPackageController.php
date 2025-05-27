<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipPackage\MembershipPackage;

class MembershipPackageController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::all();
        return view('mempackages.index-mempackages', compact('packages'));
    }

    public function create()
    {
        return view('mempackages.create-mempackages');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_name' => 'required|string|max:50',
            'duration_days' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'max_vehicles' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        MembershipPackage::create($validated);

        return redirect('membership-packages')->with('success', 'Membership package added.');
    }

    public function show($id)
    {
        $package = MembershipPackage::findOrFail($id);
        return view('membership-packages.show-membership-packages', compact('package'));
    }

    public function edit($id)
    {
        $package = MembershipPackage::findOrFail($id);
        return view('membership-packages.edit-membership-packages', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = MembershipPackage::findOrFail($id);

        $validated = $request->validate([
            'package_name' => 'required|string|max:50',
            'duration_days' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'max_vehicles' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $package->update($validated);

        return redirect('membership-packages')->with('success', 'Membership package updated.');
    }

    public function destroy($id)
    {
        $package = MembershipPackage::findOrFail($id);
        $package->delete();

        return redirect('membership-packages')->with('success', 'Membership package deleted.');
    }
}

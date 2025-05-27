<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleTyp\VehicleType;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::all();
        return view('vehicles.index-vehicle-types', compact('vehicleTypes'));
    }

    public function create()
    {
        return view('vehicles.create-vehicle-types');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
            'type_name' => 'required',
            'description' => 'required',
            'is_active' => 'nullable|in:1',
        ]);

        VehicleType::create($validated);
        return redirect('vehicle-types')->with('success', 'Vehicle Type added.');
    }

    public function show($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicles.show-vehicle-type', compact('vehicleType'));
    }

    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicles.edit-vehicle-types', compact('vehicleType'));
    }

    public function update(Request $request, $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        $vehicleType->type_name = $request->type_name;
        $vehicleType->code = $request->code;
        $vehicleType->description = $request->description;
        $vehicleType->is_active = $request->is_active;

        $vehicleType->save();

        return redirect('vehicle-types')->with('success', 'Vehicle Type updated.');
    }

    public function destroy($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();

        return redirect('vehicle-types')->with('success', 'Vehicle Type deleted.');
    }


}

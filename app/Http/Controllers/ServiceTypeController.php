<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceType\ServiceType;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::all();
        return view('servicetypes.index-servicetypes', compact('serviceTypes'));
    }

    public function create()
    {
        return view('servicetypes.create-servicetypes');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        ServiceType::create($validated);

        return redirect()->route('service-types.index')->with('success', 'Service type added.');
    }

    public function show($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        return view('servicetypes.show-servicetypes', compact('serviceType'));
    }

    public function edit($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        return view('servicetypes.edit-servicetypes', compact('serviceType'));
    }

    public function update(Request $request, $id)
    {
        $serviceType = ServiceType::findOrFail($id);

        $validated = $request->validate([
            'type_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $serviceType->update($validated);

        return redirect()->route('service-types.index')->with('success', 'Service type updated.');
    }

    public function destroy($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        $serviceType->delete();

        return redirect()->route('service-types.index')->with('success', 'Service type deleted.');
    }
}

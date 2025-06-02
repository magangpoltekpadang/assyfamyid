<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service\Service;
use App\Models\ServiceType\ServiceType;
use App\Models\Outlet\Outlet;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['type', 'outlet'])->get();
        return view('services.index-services', compact('services'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', 1)->get();
        $outlets = Outlet::where('is_active', 1)->get();
        return view('services.create-services', compact('serviceTypes', 'outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'nullable|string|max:50',
            'service_type_id' => 'nullable|exists:service_types,service_type_id',
            'price' => 'nullable|numeric',
            'estimated_duration' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'outlet_id' => 'nullable|exists:outlets,outlet_id',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service added.');
    }

    public function show($id)
    {
        $service = Service::with(['type', 'outlet'])->findOrFail($id);
        return view('services.show-services', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceTypes = ServiceType::where('is_active', 1)->get();
        $outlets = Outlet::where('is_active', 1)->get();
        return view('services.edit-services', compact('service', 'serviceTypes', 'outlets'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'service_name' => 'nullable|string|max:50',
            'service_type_id' => 'nullable|exists:service_types,service_type_id',
            'price' => 'nullable|numeric',
            'estimated_duration' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'outlet_id' => 'nullable|exists:outlets,outlet_id',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted.');
    }
}

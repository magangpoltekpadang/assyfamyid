<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer\Customer;
use App\Models\VehicleTyp\VehicleType;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index-customers', compact('customers'));
    }

    public function create()
    {
        $vehicleTypes = VehicleType::all();
        return view('customers.create-customers', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'nullable|string|max:15',
            'name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'vehicle_type_id' => 'nullable|integer|exists:vehicle_types,vehicle_type_id',
            'vehicle_color' => 'nullable|string|max:30',
            'member_number' => 'nullable|string|max:20',
            'join_date' => 'nullable|date',
            'member_expiry_date' => 'nullable|date',
            'is_member' => 'nullable|boolean',
        ]);

        Customer::create($validated);

        return redirect('customers')->with('success', 'Customer added.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $vehicleTypes = VehicleType::all();
        return view('customers.show-customers', compact('customer', 'vehicleTypes'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $vehicleTypes = VehicleType::all();
        return view('customers.edit-customers', compact('customer', 'vehicleTypes'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'plate_number' => 'nullable|string|max:15',
            'name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'vehicle_type_id' => 'nullable|integer|exists:vehicle_types,vehicle_type_id',
            'vehicle_color' => 'nullable|string|max:30',
            'member_number' => 'nullable|string|max:20',
            'join_date' => 'nullable|date',
            'member_expiry_date' => 'nullable|date',
            'is_member' => 'nullable|boolean',
        ]);

        $customer->update($validated);

        return redirect('customers')->with('success', 'Customer updated.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect('customers')->with('success', 'Customer deleted.');
    }
}

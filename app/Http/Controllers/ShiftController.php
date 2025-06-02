<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift\Shift;
use App\Models\Outlet\Outlet;
use App\Rules\TimeAfter;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('outlet')->get();
        return view('shifts.index-shift', compact('shifts'));
    }

    public function create()
    {
        $outlets = Outlet::where('is_active', 1)->get();
        return view('shifts.create-shift', compact('outlets'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'shift_name' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'outlet_id' => 'required|exists:outlets,outlet_id',
            'is_active' => 'required|boolean',
        ]);

    //     if (strtotime($validated['end_time']) <= strtotime($validated['start_time'])) {
    //     return back()->withErrors(['end_time' => 'The end time must be after start time'])->withInput();
    // }

        Shift::create($validated);

        return redirect()->route('shift.index')->with('success', 'Shift created.');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        $outlets = Outlet::where('is_active', 1)->get();

        return view('shifts.edit-shift', compact('shift', 'outlets'));
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'shift_name' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'outlet_id' => 'required|exists:outlets,outlet_id',
            'is_active' => 'required|boolean',
        ]);

        $shift->update($validated);

        return redirect()->route('shift.index')->with('success', 'Shift updated.');
    }

    public function show($id)
    {
        $shift = Shift::with('outlet')->findOrFail($id);
        return view('shifts.show-shift', compact('shift'));
    }


    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->route('shift.index')->with('success', 'Shift deleted.');
    }
}

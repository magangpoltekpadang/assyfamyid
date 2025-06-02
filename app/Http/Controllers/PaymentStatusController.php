<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentStatus\PaymentStatus;

class PaymentStatusController extends Controller
{
    public function index()
    {
        $paymentStatuses = PaymentStatus::all();
        return view('paystatuses.index-paystatuses', compact('paymentStatuses'));
    }

    public function create()
    {
        return view('paystatuses.create-paystatuses');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        PaymentStatus::create($validated);

        return redirect()->route('payment-statuses.index')->with('success', 'Payment status added.');
    }

    public function show($id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        return view('paystatuses.show-paystatuses', compact('paymentStatus'));
    }

    public function edit($id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        return view('paystatuses.edit-paystatuses', compact('paymentStatus'));
    }

    public function update(Request $request, $id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);

        $validated = $request->validate([
            'status_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $paymentStatus->update($validated);

        return redirect()->route('payment-statuses.index')->with('success', 'Payment status updated.');
    }

    public function destroy($id)
    {
        $paymentStatus = PaymentStatus::findOrFail($id);
        $paymentStatus->delete();

        return redirect()->route('payment-statuses.index')->with('success', 'Payment status deleted.');
    }
}

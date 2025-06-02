<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('paymethods.index-paymethods', compact('paymentMethods'));
    }

    public function create()
    {
        return view('paymethods.create-paymethods');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'method_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method added.');
    }

    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('paymethods.show-paymethods', compact('paymentMethod'));
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('paymethods.edit-paymethods', compact('paymentMethod'));
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'method_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated.');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction\Transaction;
use App\Models\Customer\Customer;
use App\Models\Outlet\Outlet;
use App\Models\Shift\Shift;
use App\Models\Staff\Staff;
use App\Models\PaymentStatus\PaymentStatus;


class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'outlet', 'staff', 'shift', 'paymentStatus'])->get();
        return view('transactions.index-transaction', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $outlets = Outlet::where('is_active', 1)->get();
        $staffs = Staff::where('is_active', 1)->get();
        $shifts = Shift::all();
        $paymentStatuses = PaymentStatus::all();

        return view('transactions.create-transaction', compact('customers', 'outlets', 'staffs', 'shifts', 'paymentStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_code' => 'nullable|string|max:20',
            'customer_id' => 'nullable|exists:customers,customer_id',
            'outlet_id' => 'nullable|exists:outlets,outlet_id',
            'transaction_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
            'payment_status_id' => 'nullable|exists:payment_statuses,payment_status_id',
            'gate_opened' => 'nullable|boolean',
            'staff_id' => 'nullable|exists:staff,staff_id',
            'shift_id' => 'nullable|exists:shifts,shift_id',
            'receipt_printed' => 'nullable|boolean',
            'whatsapp_sent' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        Transaction::create($validated);

        return redirect()->route('transaction.index')->with('success', 'Transaction created.');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['customer', 'outlet', 'staff', 'shift', 'paymentStatus'])->findOrFail($id);
        return view('transactions.show-transaction', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $customers = Customer::all();
        $outlets = Outlet::where('is_active', 1)->get();
        $staffs = Staff::where('is_active', 1)->get();
        $shifts = Shift::all();
        $paymentStatuses = PaymentStatus::all();

        return view('transactions.edit-transaction', compact('transaction', 'customers', 'outlets', 'staffs', 'shifts', 'paymentStatuses'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'transaction_code' => 'nullable|string|max:20',
            'customer_id' => 'nullable|exists:customers,customer_id',
            'outlet_id' => 'nullable|exists:outlets,outlet_id',
            'transaction_date' => 'nullable|date',
            'subtotal' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'final_price' => 'nullable|numeric',
            'payment_status_id' => 'nullable|exists:payment_statuses,payment_status_id',
            'gate_opened' => 'nullable|boolean',
            'staff_id' => 'nullable|exists:staff,staff_id',
            'shift_id' => 'nullable|exists:shifts,shift_id',
            'receipt_printed' => 'nullable|boolean',
            'whatsapp_sent' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('transaction.index')->with('success', 'Transaction updated.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transaction.index')->with('success', 'Transaction deleted.');
    }
}

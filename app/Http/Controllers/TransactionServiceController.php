<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service\Service;
use App\Models\Staff\Staff;
use App\Models\Transaction\Transaction;
use App\Models\TransactionService\TransactionService;

class TransactionServiceController extends Controller
{
    public function index()
    {
        $transactionServices = TransactionService::with(['transaction', 'service', 'staff'])->get();
        return view('transactionservices.index-transservice', compact('transactionServices'));
    }

    public function create()
    {
        $transactions = Transaction::all();
        $services = Service::all();
        $staffs = Staff::where('is_active', 1)->get();

        return view('transactionservices.create-transservice', compact('transactions', 'services', 'staffs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'transaction_id' => 'required|exists:transactions,transaction_id',
        'service_id' => 'required|exists:services,service_id',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'staff_id' => 'required|exists:staff,staff_id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'status' => 'required|in:pending,in_progress,completed,canceled',
        'notes' => 'nullable|string',
    ]);

        $validated['total_price'] = ($validated['unit_price'] * $validated['quantity']) - ($validated['discount'] ?? 0);

        TransactionService::create($validated);

        return redirect()->route('transaction-services.index')->with('success', 'Transaction Service created successfully.');
    }

    public function show($id)
    {
        $transactionService = TransactionService::with(['transaction', 'service', 'staff'])->findOrFail($id);
        return view('transactionservices.show-transservice', compact('transactionService'));
    }

    public function edit($id)
    {
        $transactionService = TransactionService::findOrFail($id);
        $transactions = Transaction::all();
        $services = Service::all();
        $staffs = Staff::where('is_active', 1)->get();

        return view('transactionservices.edit-transservice', compact('transactionService', 'transactions', 'services', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        $transactionService = TransactionService::findOrFail($id);

    $validated = $request->validate([
        'transaction_id' => 'required|exists:transactions,transaction_id',
        'service_id' => 'required|exists:services,service_id',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'staff_id' => 'required|exists:staff,staff_id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'status' => 'required|in:pending,in_progress,completed,canceled',
        'notes' => 'nullable|string',
    ]);

        $validated['total_price'] = ($validated['unit_price'] * $validated['quantity']) - ($validated['discount'] ?? 0);

        $transactionService->update($validated);

        return redirect()->route('transaction-services.index')->with('success', 'Transaction Service updated successfully.');
    }

    public function destroy($id)
    {
        $transactionService = TransactionService::findOrFail($id);
        $transactionService->delete();

        return redirect()->route('transaction-services.index')->with('success', 'Transaction Service deleted.');
    }
}

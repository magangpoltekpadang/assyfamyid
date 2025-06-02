<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationStatus\NotificationStatus;

class NotificationStatusController extends Controller
{
    public function index()
    {
        $notificationStatuses = NotificationStatus::all();
        return view('notifstatuses.index-notificationstatuses', compact('notificationStatuses'));
    }

    public function create()
    {
        return view('notifstatuses.create-notificationstatuses');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        NotificationStatus::create($validated);

        return redirect()->route('notification-statuses.index')->with('success', 'Notification status added.');
    }

    public function show($id)
    {
        $notificationStatus = NotificationStatus::findOrFail($id);
        return view('notifstatuses.show-notificationstatuses', compact('notificationStatus'));
    }

    public function edit($id)
    {
        $notificationStatus = NotificationStatus::findOrFail($id);
        return view('notifstatuses.edit-notificationstatuses', compact('notificationStatus'));
    }

    public function update(Request $request, $id)
    {
        $notificationStatus = NotificationStatus::findOrFail($id);

        $validated = $request->validate([
            'status_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $notificationStatus->update($validated);

        return redirect()->route('notification-statuses.index')->with('success', 'Notification status updated.');
    }

    public function destroy($id)
    {
        $notificationStatus = NotificationStatus::findOrFail($id);
        $notificationStatus->delete();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationType\NotificationType;

class NotificationTypeController extends Controller
{
    public function index()
    {
        $notificationTypes = NotificationType::all();
        return view('notiftypes.index-notificationtypes', compact('notificationTypes'));
    }

    public function create()
    {
        return view('notiftypes.create-notificationtypes');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'template_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        NotificationType::create($validated);

        return redirect()->route('notification-types.index')->with('success', 'Notification type added.');
    }

    public function show($id)
    {
        $notificationType = NotificationType::findOrFail($id);
        return view('notiftypes.show-notificationtypes', compact('notificationType'));
    }

    public function edit($id)
    {
        $notificationType = NotificationType::findOrFail($id);
        return view('notiftypes.edit-notificationtypes', compact('notificationType'));
    }

    public function update(Request $request, $id)
    {
        $notificationType = NotificationType::findOrFail($id);

        $validated = $request->validate([
            'type_name' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:20',
            'template_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $notificationType->update($validated);

        return redirect()->route('notification-types.index')->with('success', 'Notification type updated.');
    }

    public function destroy($id)
    {
        $notificationType = NotificationType::findOrFail($id);
        $notificationType->delete();
    }

}

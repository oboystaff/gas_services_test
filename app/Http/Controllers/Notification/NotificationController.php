<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\CreateNotificationRequest;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function index()
    {
        $pageTitle = "Notification Team Page";

        $notifications = Notification::orderBy('created_at', 'DESC')->get();

        return view('notifications.index', compact('notifications', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = "Notification Team Page";

        return view('notifications.create', compact('pageTitle'));
    }

    public function store(CreateNotificationRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        Notification::create($data);

        return redirect()->route('notifications.index')->with('status', 'Notification team created successfully.');
    }

    public function show(Notification $notification)
    {
        $pageTitle = "Notification Team Page";

        return view('notifications.show', compact('notification', 'pageTitle'));
    }

    public function edit(Notification $notification)
    {
        $pageTitle = "Notification Team Page";

        return view('notifications.edit', compact('notification', 'pageTitle'));
    }

    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        $notification->update($request->validated());

        return redirect()->route('notifications.index')->with('status', 'Notification team updated successfully.');
    }
}

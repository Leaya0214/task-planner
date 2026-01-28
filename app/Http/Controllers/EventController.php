<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Event::class);

        if (Gate::allows('manage-all-events')) {
            $events = Event::with('task')->latest()->get();
        } else {
            // Get events for tasks assigned to the authenticated user
            $events = Event::whereHas('task.assignee', function ($q) {
                $q->where('id', Auth::id());
            })->with('task')->latest()->get();
        }

        return Inertia::render('Event/Index', [
            'events' => $events,
            'canManageAll' => Gate::allows('manage-all-events'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Event::class);

        $tasks = Task::all(['id', 'title']);

        return Inertia::render('Event/Create', [
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        Event::create($request->validated());

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load('task');

        return Inertia::render('Event/Show', [
            'event' => $event,
        ]);
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $tasks = Task::all(['id', 'title']);

        return Inertia::render('Event/Edit', [
            'event' => $event,
            'tasks' => $tasks,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->update($request->validated());

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}

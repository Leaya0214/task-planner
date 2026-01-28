<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Services\CalendarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    protected CalendarService $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
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

    public function calendar()
    {
        $this->authorize('viewAny', Event::class);

        $events = $this->calendarService->getEventsForUser(Auth::user());
        $formattedEvents = $this->calendarService->formatEventsForFullCalendar($events, Auth::user());

        return Inertia::render('Event/Calendar', [
            'events' => $formattedEvents,
            'can' => [
                'create' => $this->authorize('create', Event::class),
                'manageAll' => Gate::allows('manage-all-events'),
            ]
        ]);
    }

    /**
     * API endpoint for FullCalendar (JSON response)
     */
    public function calendarEvents(Request $request)
    {
        $this->authorize('viewAny', Event::class);

        $events = $this->calendarService->getEventsForUser(
            Auth::user(),
            $request->input('start'),
            $request->input('end')
        );

        return response()->json(
            $this->calendarService->formatEventsForFullCalendar($events, Auth::user())
        );
    }

    /**
     * Update event date/time via drag & drop
     */
    public function updateEventDate(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully'
        ]);
    }

    /**
     * Quick create event from calendar
     */
    public function quickStore(Request $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ]);

        $event = Event::create(array_merge($validated, [
            'created_by' => Auth::id()
        ]));

        return response()->json([
            'success' => true,
            'event' => $this->calendarService->formatEventForFullCalendar($event, Auth::user()),
            'message' => 'Event created successfully'
        ]);
    }
}

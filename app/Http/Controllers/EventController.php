<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
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

    public function store(EventRequest $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validated();

        Event::create(array_merge($validated, [
            'created_by' => Auth::id()
        ]));

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

    public function update(EventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validated();

        $event->update($validated);

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
        $tasks = Task::all(['id', 'title']);

        return Inertia::render('Event/Calendar', [
            'events' => $formattedEvents,
            'tasks' => $tasks,
            'can' => [
                'create' => Gate::allows('create-event'),
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
    public function quickStore(EventRequest $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validated();

        Event::create(array_merge($validated, [
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('events.calendar')
            ->with('success', 'Event created successfully');
    }
}

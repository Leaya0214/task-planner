<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class CalendarService
{
    /**
     * Get events for user with role-based filtering
     */
    public function getEventsForUser(User $user, ?string $start = null, ?string $end = null)
    {
        $query = Event::with(['task', 'createdBy']);

        // Apply date range filter if provided
        if ($start && $end) {
            $query->whereBetween('date', [$start, $end]);
        }

        // Apply role-based filtering (following your existing pattern)
        if (Gate::allows('manage-all-events')) {
            // Admin sees all events
            return $query->get();
        } else {
            // Employee sees only events for tasks assigned to them
            return $query->whereHas('task.assignee', function ($q) use ($user) {
                $q->where('id', $user->id);
            })->get();
        }
    }

    /**
     * Format events for FullCalendar
     */
    public function formatEventsForFullCalendar($events, User $user)
    {
        return $events->map(function ($event) use ($user) {
            return $this->formatEventForFullCalendar($event, $user);
        });
    }

    /**
     * Format single event for FullCalendar
     */
    public function formatEventForFullCalendar(Event $event, User $user)
    {
        return [
            'id' => $event->id,
            'title' => $event->name,
            'start' => $this->formatDateTime($event->date, $event->start_time),
            'end' => $this->formatDateTime($event->date, $event->end_time),
            'color' => $this->getEventColor($event),
            'textColor' => '#ffffff',
            'extendedProps' => [
                'description' => $event->description ?? '',
                'task_id' => $event->task_id,
                'task_title' => $event->task?->title,
                'created_by' => $event->createdBy->name,
                'editable' => $user->can('update', $event),
                'deletable' => $user->can('delete', $event),
                'priority' => $event->task?->priority ?? 'medium',
            ],
            'allDay' => false,
        ];
    }

    /**
     * Format date time for FullCalendar
     */
    private function formatDateTime($date, $time)
    {
        if (strlen($time) == 5) { // H:i format
            $time .= ':00';
        }
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $date . ' ' . $time,
            'Asia/Dhaka'
        )->toIso8601String();
    }

    /**
     * Get event color based on priority or type
     */
    private function getEventColor(Event $event): string
    {
        // If event has a task, use task priority colors
        if ($event->task_id) {
            return match ($event->task->priority ?? 'medium') {
                'high' => '#ef4444', // Red
                'medium' => '#f59e0b', // Orange
                'low' => '#10b981', // Green
                default => '#3b82f6', // Blue
            };
        }

        // Standalone events
        return '#8b5cf6'; // Purple
    }

    /**
     * Get events statistics for dashboard
     */
    public function getCalendarStats(User $user, string $startDate, string $endDate)
    {
        $events = $this->getEventsForUser($user, $startDate, $endDate);

        return [
            'total' => $events->count(),
            'task_events' => $events->whereNotNull('task_id')->count(),
            'standalone_events' => $events->whereNull('task_id')->count(),
            'upcoming' => $events->where('date', '>=', now()->format('Y-m-d'))->count(),
        ];
    }
}

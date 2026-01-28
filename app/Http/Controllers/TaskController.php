<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Task::class);

        if (Gate::allows('manage-all-tasks')) {
            $tasks = Task::with('assignee')->latest()->get();
        } else {
            $tasks = Task::where('assigned_to', Auth::id())
                ->with('assignee')
                ->latest()
                ->get();
        }
        // JSON response for testing
        if (request()->wantsJson()) {
            return response()->json($tasks);
        }

        return Inertia::render('Task/Index', [
            'tasks' => $tasks,
            'canManageAll' => Gate::allows('manage-all-tasks'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Task::class);

        $employees = User::where('role', 'employee')->get(['id', 'name']);

        return Inertia::render('Task/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);

        Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load('assignee', 'events');

        return Inertia::render('Task/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $employees = User::where('role', 'employee')->get(['id', 'name']);

        return Inertia::render('Task/Edit', [
            'task' => $task,
            'employees' => $employees,
        ]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskList $list)
    {
        $currentFilter = $this->taskFilter($request);
        $tasksQuery = $list->tasks()->with('list')->orderBy('created_at', 'desc');

        if ($currentFilter === 'open') {
            $tasksQuery->pending();
        }

        if ($currentFilter === 'done') {
            $tasksQuery->completed();
        }

        $tasks = $tasksQuery->paginate(12)->withQueryString();

        return view('tasks.index', compact('list', 'tasks', 'currentFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TaskList $list)
    {
        $task = new Task([
            'is_completed' => false,
            'list_id' => $list->id,
        ]);

        return view('tasks.create', compact('list', 'task'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TaskList $list)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $list->tasks()->create($data + ['is_completed' => $request->boolean('is_completed')]);

        return redirect()->route('lists.show', $list)->with('success', 'Nota creata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $list, Task $task)
    {
        $task->load('list');

        return view('tasks.show', compact('list', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $list, Task $task)
    {
        return view('tasks.edit', compact('list', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $task->update($data + ['is_completed' => $request->boolean('is_completed')]);

        return redirect()->route('lists.show', $list)->with('success', 'Nota aggiornata.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $list, Task $task)
    {
        $task->delete();

        return redirect()->route('lists.show', $list)->with('success', 'Nota eliminata.');
    }

    /**
     * Toggle completion state for a task.
     */
    public function toggle(Request $request, TaskList $list, Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json(['is_completed' => $task->is_completed]);
        }

        return redirect()->back();
    }

    private function taskFilter(Request $request): string
    {
        $filter = $request->query('filter', 'all');

        return in_array($filter, ['all', 'open', 'done'], true) ? $filter : 'all';
    }
}

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
    public function index(TaskList $list)
    {
        $tasks = $list->tasks()->with('list')->orderBy('created_at', 'desc')->paginate(12);

        return view('tasks.index', compact('list', 'tasks'));
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
}

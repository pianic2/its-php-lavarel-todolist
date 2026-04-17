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
    public function index()
    {
        $tasks = Task::with('list')->orderBy('created_at', 'desc')->paginate(12);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task([
            'is_completed' => false,
        ]);
        $lists = TaskList::orderBy('name')->get();

        return view('tasks.create', compact('task', 'lists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
            'list_id' => 'nullable|integer|exists:lists,id'
        ]);

        Task::create($data + ['is_completed' => $request->boolean('is_completed')]);

        return redirect()->route('tasks.index')->with('success', 'Nota creata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('list');

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $lists = TaskList::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'lists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
            'list_id' => 'nullable|integer|exists:lists,id'
        ]);

        $task->update($data + ['is_completed' => $request->boolean('is_completed')]);

        return redirect()->route('tasks.index')->with('success', 'Nota aggiornata.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Nota eliminata.');
    }

    /**
     * Toggle completion state for a task.
     */
    public function toggle(Request $request, Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json(['is_completed' => $task->is_completed]);
        }

        return redirect()->back();
    }
}

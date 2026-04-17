<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lists = TaskList::withCount('tasks')->orderBy('name')->paginate(12);

        return view('lists.index', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $list = new TaskList();

        return view('lists.create', compact('list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        TaskList::create($data);

        return redirect()->route('lists.index')->with('success', 'Lista creata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TaskList $list)
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

        return view('lists.show', compact('list', 'tasks', 'currentFilter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $list)
    {
        return view('lists.edit', compact('list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $list->update($data);

        return redirect()->route('lists.index')->with('success', 'Lista aggiornata.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $list)
    {
        $list->tasks()->delete();
        $list->delete();

        return redirect()->route('lists.index')->with('success', 'Lista eliminata.');
    }

    private function taskFilter(Request $request): string
    {
        $filter = $request->query('filter', 'all');

        return in_array($filter, ['all', 'open', 'done'], true) ? $filter : 'all';
    }
}

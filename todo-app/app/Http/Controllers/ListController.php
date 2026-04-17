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
    public function show(TaskList $list)
    {
        $tasks = $list->tasks()->with('list')->orderBy('created_at', 'desc')->paginate(12);

        return view('lists.show', compact('list', 'tasks'));
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
}

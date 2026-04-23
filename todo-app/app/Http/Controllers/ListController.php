<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ListShared;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $lists = $user->lists()->withCount('tasks')->orderBy('name')->paginate(12);
        } else {
            $lists = TaskList::withCount('tasks')->orderBy('name')->paginate(12);
        }

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

        $list = TaskList::create($data);

        if ($user = Auth::user()) {
            $list->users()->attach($user->id);
        }

        return redirect()->route('lists.index')->with('success', 'Lista creata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TaskList $list)
    {
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

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
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

        return view('lists.edit', compact('list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list)
    {
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

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
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

        $list->tasks()->delete();
        $list->delete();

        return redirect()->route('lists.index')->with('success', 'Lista eliminata.');
    }

    /**
     * Share a list with another user by email.
     */
    public function share(Request $request, TaskList $list)
    {
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user->lists()->where('lists.id', $list->id)->exists()) {
            return redirect()->back()->with('warning', 'L\'utente ha già accesso a questa lista.');
        }

        $list->users()->attach($user->id);

        // Send notification to the invited user
        try {
            $user->notify(new ListShared($list, Auth::user()));
        } catch (\Throwable $e) {
            // swallow notification errors but log them
            logger()->error('Failed to send list shared notification: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Lista condivisa.');
    }

    /**
     * Revoke a user's access to the list.
     */
    public function unshare(TaskList $list, User $user)
    {
        if (! Auth::user() || ! Auth::user()->lists()->where('lists.id', $list->id)->exists()) {
            abort(403);
        }

        $list->users()->detach($user->id);

        return redirect()->back()->with('success', 'Accesso revocato.');
    }

    private function taskFilter(Request $request): string
    {
        $filter = $request->query('filter', 'all');

        return in_array($filter, ['all', 'open', 'done'], true) ? $filter : 'all';
    }
}

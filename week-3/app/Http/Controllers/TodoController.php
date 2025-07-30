<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TodoController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
        public function index()
        {
            $user = \Illuminate\Support\Facades\Auth::user();

            $todos = $user->is_admin
                ? Todo::with('user')->get()->groupBy('status')
                : $user->todos()->get()->groupBy('status');

            return view('todos.index', [
                'todosByStatus' => $todos,
            ]);
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:not_started,todo,in_progress,finished',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo created!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        // Example: Show the todo details
        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        // Example: Show the edit form for the todo
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:not_started,todo,in_progress,finished',
        ]);

        $todo->update($request->only('title', 'description', 'status'));

        return redirect()->route('todos.index')->with('success', 'Todo updated!');
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'todos' => 'required|array',
            'status' => 'required|in:not_started,todo,in_progress,finished',
        ]);

        foreach ($request->todos as $todoId) {
            $todo = Todo::find($todoId);

            if ($todo && ($todo->user_id === auth()->id() || auth()->user()->is_admin)) {
                $todo->update(['status' => $request->status]);
            }
        }

        return response()->json(['message' => 'Todos updated']);
    }



    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Todo deleted!');
    }

}

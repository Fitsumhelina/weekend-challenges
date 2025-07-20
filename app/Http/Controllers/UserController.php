<?php

namespace App\Http\Controllers;

// use App\Models\User
use App\Policies\GenericPolicy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $genericPolicy;
    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }
    public function index()
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    public function show(User $id)
    {
        if (!$this->genericPolicy->view(Auth::user(), $id)) {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function create()
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }
        return view('users.create');
    }
    public function store(Request $request)
    {
        if (!$this->genericPolicy->create(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ]);
        User::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    public function edit(User $user)
    {
        if (!$this->genericPolicy->view(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }
        $allUsers = User::all();
        return view('users.edit', compact('user', 'allUsers'));
    }
    public function update(Request $request, User $user)
    {
        if (!$this->genericPolicy->update(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
         ]);

        User::update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');  
    }

    public function destroy(User $user)
    {
        if (!$this->genericPolicy->delete(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
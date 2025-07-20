<?php

namespace App\Http\Controllers;

use App\Policies\GenericPolicy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role; // Import the Role model
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\View\View; // Explicitly import View

class UserController extends Controller
{
    protected $genericPolicy;

    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }

    public function index(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load roles for each user to display them in the table
        $users = User::with('roles')->paginate(10);
        $roles = Role::all(); // Fetch all roles to pass to the create modal
        
       
        // If AJAX request, return only the result partial
        if (request()->ajax()) {
            return view('users.result', compact('users'));
        }

        return view('users.index', compact('users', 'roles'));
    }

    public function show($id): View // Changed type hint to match route model binding expectation for ID
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) { // Policy check on a new User instance if $id is not a model
            abort(403, 'Unauthorized action.');
        }
        // Eager load roles for the specific user
        $user = User::with('roles')->findOrFail($id);
        return view('users.partials.show', compact('user'));
    }

    public function create(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::all(); // Fetch all roles to populate the form
        return view('users.create', compact('roles')); // This view is not directly used by the modal, but kept for completeness
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // Added min length for password
            'roles' => 'nullable|array', // Validate roles as an array
            'roles.*' => 'exists:roles,name', // Validate each role name exists in the roles table
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // Hash the password
        ]);

        // Assign roles to the user
        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View // Changed return type to View as it renders a partial
    {
        if (!$this->genericPolicy->view(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::all(); // Fetch all roles to populate the form
        // Pass the user and all roles to the form partial
        return view('users.partials.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Exclude current user's email
            'password' => 'nullable|string|min:8', // Password is optional for update
            'roles' => 'nullable|array', // Validate roles as an array
            'roles.*' => 'exists:roles,name', // Validate each role name exists
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        // Only update password if provided
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save(); // Save the user data

        // Sync roles for the user
        $user->syncRoles($data['roles'] ?? []); // Use null coalescing to handle empty roles array

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if (!$this->genericPolicy->delete(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

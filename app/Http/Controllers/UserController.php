<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Income;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role; 
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View; 

class UserController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $search = request('search');
        $searchCategory = request('category', 'name'); // Default to 'name' if not provided
        $perPage = request('per_page', 10);
        $date = request('date');

        $users = User::orderBy('created_at', 'desc')
            ->with(['roles'])
            ->when($search, function ($query, $search) use ($searchCategory) {
                $query->where(function ($q) use ($search, $searchCategory) {
                    if ($searchCategory === 'name') {
                        $q->where('name', 'like', '%' . $search . '%');
                    } elseif ($searchCategory === 'role') {
                        $q->whereHas('roles', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                    } else {
                        $q->where('name', 'like', '%' . $search . '%');
                    }
                });
            })
            ->paginate($perPage)
            ->appends(request()->query());

        $roles = Role::all();

        if (request()->ajax()) {
            return view('user.result', compact('users'));
        }

        return view('user.index', compact('users', 'roles'));
    }

    public function show($id): View // Changed type hint to match route model binding expectation for ID
    {
        if (!$this->genericPolicy->view(Auth::user(), new User())) { // Policy check on a new User instance if $id is not a model
            abort(403, 'Unauthorized action.');
        }
        $user = User::with('roles')->findOrFail($id);
        $income =Income::where('source', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalDebt = Income::where('source', $id)->sum('debt');

        return view('user.partials.show', compact('user', 'income','totalDebt')); 
    }

    public function create(): View
    {
        if (!$this->genericPolicy->create(Auth::user(), new User())) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        return view('user.partials.form', compact('roles')); // This view is not directly used by the modal, but kept for completeness
    }

    public function store(Request $request): RedirectResponse
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

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View // Changed return type to View as it renders a partial
    {
        if (!$this->genericPolicy->view(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::all(); 
        return view('user.partials.form', compact('user', 'roles'));
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

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if (!$this->genericPolicy->delete(Auth::user(), $user)) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}

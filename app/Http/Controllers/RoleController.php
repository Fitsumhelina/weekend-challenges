<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
   protected $genericPolicy;

    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }

   public function index()
{
    $roles = Role::with('permissions')->paginate(10); // eager load permissions
    $allPermissions = Permission::all(); // fetch all permissions for checkboxes

    if (request()->ajax()) {
        return view('user.roles.result', compact('roles'))->render();
    }

    return view('roles.index', compact('roles', 'allPermissions'));
}
    public function create()
    {
        if (!$this->genericPolicy->view(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        if (!$this->genericPolicy->create(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
          if (!$this->genericPolicy->view(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        $allPermissions = Permission::all();
        return view('roles.partials.form', compact('role', 'allPermissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
          if (!$this->genericPolicy->update(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        Log::info($request->all());
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
          if (!$this->genericPolicy->delete(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        $role = Role::findOrFail($role->id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
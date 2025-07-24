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

    {    if (!$this->genericPolicy->view(Auth::user(), new Role())) {
                abort(403, 'Unauthorized action.');
            }
    
        $query = Role::with('permissions');

        if ($search = request('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->get();
        $allPermissions = Permission::all(); 

        if (request()->ajax()) {
            return view('role.result', compact('roles'))->render();
        }

        return view('role.index', compact('roles', 'allPermissions'));
    }
    public function create()
    {
        if (!$this->genericPolicy->create(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }

        
        $permissions = Permission::all();
        return view('role.partials.form', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        if (!$this->genericPolicy->create(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::create([
            'name' => $request->input('name')
        ]);

        $permissions = $request->input('permissions', []);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('role.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
          if (!$this->genericPolicy->view(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }
        $allPermissions = Permission::all();
        return view('role.partials.form', compact('role', 'allPermissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        if (!$this->genericPolicy->update(Auth::user(), new Role())) {
            abort(403, 'Unauthorized action.');
        }

        $role->update([
            'name' => $request->input('name')
        ]);

        $permissions = $request->input('permissions', []);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
          if (!$this->genericPolicy->delete(Auth::user(), $role)) {
            abort(403, 'Unauthorized action.');
        }
        $role = Role::findOrFail($role->id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }
}
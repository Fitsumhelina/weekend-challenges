<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Redirect;

class PermissionController extends Controller
{   protected $genericPolicy;

    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }

    public function index(Request $request)
    {
        if (!$this->genericPolicy->view(Auth::user(), new Permission())) {
            abort(403, 'Unauthorized action.');
        }
        $search = $request->query('search');
        $sortColumn = $request->query('sort', 'name');
        $sortDirection = $request->query('direction', 'asc');
        $perPage = $request->query('per_page', 10);

        $permissions = Permission::query()
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
            })
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($perPage);

        if ($request->ajax()) {
            return view('permission.result', compact('permissions'))->render();
        }

        return view('permission.index', compact('permissions', 'perPage'));
    }

}
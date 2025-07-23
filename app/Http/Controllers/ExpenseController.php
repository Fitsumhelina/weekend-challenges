<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpenseExport;

class ExpenseController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected $genericPolicy;

    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }

    public function index(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
            abort(403, 'Unauthorized action.');
        }

       $search = request('search');
        $perPage = request('per_page', 10);
        $date = request('date');

        $expenses = Expense::orderBy('created_at', 'desc')->with(['createdByUser', 'updatedByUser'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%')
                        ->orWhereRaw("DATE_FORMAT(date, '%Y-%m-%d') LIKE ?", ['%' . $search . '%']);
                });
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->paginate($perPage)
            ->appends(request()->query()); 

        $users = User::all();

        if (request()->ajax()) {
            return view('expense.result', compact('expenses'));
        }

        return view('expense.index', compact('expenses', 'users'));
    }

    public function create(): View
    {
        if (!$this->genericPolicy->create(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        return view('expense.partials.form');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string', 
            'amount' => 'required|numeric',
            'date' => 'required|date', 
            'description' => 'nullable|string|max:255', 
        ]);

        $data['created_by'] = Auth::id(); 
        $data['updated_by'] = Auth::id(); 

        Expense::create($data);
        return Redirect::route('expense.index')->with('success', 'Expense created successfully.');
    }

    public function show($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::with('createdByUser', 'updatedByUser')->findOrFail($id);
        return view('expense.partials.show', compact('expense'));
    }

    public function edit($id): View
    {
        if (!$this->genericPolicy->update(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);
        return view('expense.partials.form', compact('expense'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date', 
            'description' => 'nullable|string|max:255', 
        ]);

        $data['updated_by'] = Auth::id(); 

        $expense->update($data);
        return Redirect::route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        if (!$this->genericPolicy->delete(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return Redirect::route('expense.index')->with('success', 'Expense deleted successfully.');
    }
    public function export()
    {
        return Excel::download(new ExpenseExport, 'expenses.xlsx');
    }
}
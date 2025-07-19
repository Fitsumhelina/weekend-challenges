<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Policies\GenericPolicy;

class IncomeController extends Controller
{
   use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

   protected $genericPolicy;

   public function __construct(GenericPolicy $genericPolicy)
   {
       $this->genericPolicy = $genericPolicy;
   }

   public function index(): View
   {
       $this->authorize('view', Income::class);
       $incomes = Income::latest()->paginate(10);
       return view('income.index', compact('incomes'));
   }


    public function create(): View
    {
         $this->authorize('create', Income::class);
         return view('income.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Income::class);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'source' => 'required',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        Income::create($data);
        return Redirect::route('income.index')->with('success', 'Income created successfully.');
    }


    public function show($id): View
    {
        $income = Income::findOrFail($id);
        $this->authorize('view', $income);
        return view('income.show', compact('income'));
    }


    public function edit($id): View
    {
        $income = Income::findOrFail($id);
        $this->authorize('update', $income);
        return view('income.edit', compact('income'));
    }


    public function update(Request $request, $id): RedirectResponse
    {
        $income = Income::findOrFail($id);
        $this->authorize('update', $income);
        $data = $request->validate([    
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'source' => 'required',
            'date' => 'required|date',
        ]);
        $income->update($data);
        return Redirect::route('income.index')->with('success', 'Income updated successfully.');
    }


    public function destroy($id): RedirectResponse
    {
        $income = Income::findOrFail($id);
        $this->authorize('delete', $income);
        $income->delete();
        return Redirect::route('income.index')->with('success', 'Income deleted successfully.');
    }
}


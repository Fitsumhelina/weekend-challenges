<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
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
       if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }

       $incomes = Income::paginate(10);
       return view('income.index', compact('incomes'));
   }


    public function create(): View
    {   
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
        }

       return view('income.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
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
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
        $income = Income::findOrFail($id);
        return view('partials.income.show', compact('income'));
    }


    public function edit($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
        $income = Income::findOrFail($id);
        return view('partials.income.edit', compact('income'));
    }


    public function update(Request $request, $id): RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }

        $income = Income::findOrFail($id);
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
        if (!$this->genericPolicy->delete(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }

        $income = Income::findOrFail($id);
        $income->delete();
        return Redirect::route('income.index')->with('success', 'Income deleted successfully.');
    }
}


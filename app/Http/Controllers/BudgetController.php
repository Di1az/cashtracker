<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Auth::user()->budgets()->get();
        return view('dashboard', [
            'budgets' => $budgets //pasar datos del controller a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budgets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        $budget = Auth::user()->budgets()->create($request->validated());
        return redirect()->route('dashboard')->with('success', 'Presupuesto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Authorize('update', 'budget')] //Policy para evitar que otros usuarios puedan ver y editar presupuestos ajenos
    public function edit(Budget $budget)
    {
        return view('budgets.edit', [ 
            'budget' => $budget
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', 'budget')]
    public function update(BudgetRequest $request, Budget $budget)
    {
        $budget->update($request->validated());
        return redirect()->route('dashboard')->with('success', 'Presupuesto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

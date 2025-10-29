<?php

namespace App\Http\Controllers;

use App\Models\Byproduct;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ByproductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $byproducts = Byproduct::where('farmer_id', $request->user()->id)
            ->with('harvest.plot')
            ->latest()
            ->paginate(12);

        return Inertia::render('Byproducts/Index', [
            'byproducts' => $byproducts,
        ]);
    }

    public function create(Request $request)
    {
        $harvests = $request->user()->harvests()
            ->with('plot')
            ->where('status', 'completed')
            ->latest()
            ->get();

        return Inertia::render('Byproducts/Create', [
            'harvests' => $harvests,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'harvest_id' => 'required|exists:harvests,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $byproduct = Byproduct::create([
            'farmer_id' => $request->user()->id,
            ...$validated,
        ]);

        return redirect()->route('byproducts.index')->with('success', 'Byproduct created successfully');
    }

    public function show(Byproduct $byproduct)
    {
        $this->authorize('view', $byproduct);

        $byproduct->load(['harvest.plot', 'farmer.farmerProfile']);

        return Inertia::render('Byproducts/Show', [
            'byproduct' => $byproduct,
        ]);
    }

    public function edit(Byproduct $byproduct)
    {
        $this->authorize('update', $byproduct);

        return Inertia::render('Byproducts/Edit', [
            'byproduct' => $byproduct,
        ]);
    }

    public function update(Request $request, Byproduct $byproduct)
    {
        $this->authorize('update', $byproduct);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $byproduct->update($validated);

        return redirect()->route('byproducts.show', $byproduct)->with('success', 'Byproduct updated successfully');
    }

    public function destroy(Byproduct $byproduct)
    {
        $this->authorize('delete', $byproduct);

        $byproduct->delete();

        return redirect()->route('byproducts.index')->with('success', 'Byproduct deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $products = Product::where('farmer_id', $request->user()->id)
            ->with('harvest.plot')
            ->latest()
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function create(Request $request)
    {
        $harvests = $request->user()->harvests()
            ->with('plot')
            ->where('status', 'completed')
            ->latest()
            ->get();

        return Inertia::render('Products/Create', [
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

        $product = Product::create([
            'farmer_id' => $request->user()->id,
            ...$validated,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $product->load(['harvest.plot', 'farmer.farmerProfile']);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\PlotImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlotController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $plots = Plot::where('farmer_id', $request->user()->id)
            ->with('images')
            ->withCount('harvests')
            ->latest()
            ->paginate(12);

        return Inertia::render('Plots/Index', [
            'plots' => $plots,
        ]);
    }

    public function create()
    {
        return Inertia::render('Plots/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'size' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'tree_count' => 'nullable|integer|min:0',
            'potential_harvest' => 'nullable|integer|min:0',
            'harvest_frequency' => 'required|in:weekly,biweekly,monthly,custom',
            'custom_frequency' => 'nullable|string|max:255',
            'is_harvest_sold' => 'boolean',
            'can_deliver' => 'boolean',
            'available_categories' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048',
        ]);

        $plot = Plot::create([
            'farmer_id' => $request->user()->id,
            ...$validated,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('plot-images', 'public');
                PlotImage::create([
                    'plot_id' => $plot->id,
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('plots.index')->with('success', 'Plot created successfully');
    }

    public function show(Plot $plot)
    {
        $this->authorize('view', $plot);

        $plot->load(['images', 'harvests' => function ($query) {
            $query->latest()->take(5);
        }]);

        return Inertia::render('Plots/Show', [
            'plot' => $plot,
        ]);
    }

    public function edit(Plot $plot)
    {
        $this->authorize('update', $plot);

        $plot->load('images');

        return Inertia::render('Plots/Edit', [
            'plot' => $plot,
        ]);
    }

    public function update(Request $request, Plot $plot)
    {
        $this->authorize('update', $plot);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'size' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'tree_count' => 'nullable|integer|min:0',
            'potential_harvest' => 'nullable|integer|min:0',
            'harvest_frequency' => 'required|in:weekly,biweekly,monthly,custom',
            'custom_frequency' => 'nullable|string|max:255',
            'is_harvest_sold' => 'boolean',
            'can_deliver' => 'boolean',
            'available_categories' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
        ]);

        $plot->update($validated);

        // Handle image deletions
        if ($request->has('delete_images')) {
            $imagesToDelete = PlotImage::whereIn('id', $request->delete_images)
                ->where('plot_id', $plot->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $maxOrder = $plot->images()->max('order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('plot-images', 'public');
                PlotImage::create([
                    'plot_id' => $plot->id,
                    'image_path' => $path,
                    'order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('plots.show', $plot)->with('success', 'Plot updated successfully');
    }

    public function destroy(Plot $plot)
    {
        $this->authorize('delete', $plot);

        // Delete all images
        foreach ($plot->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $plot->delete();

        return redirect()->route('plots.index')->with('success', 'Plot deleted successfully');
    }
}

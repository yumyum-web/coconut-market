<?php

namespace App\Http\Controllers;

use App\Models\BidTimeWindow;
use App\Models\Harvest;
use App\Models\HarvestBid;
use App\Models\HarvestCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HarvestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Harvest::with(['plot', 'farmer', 'bids']);

        if ($request->user()->isFarmer()) {
            $query->where('farmer_id', $request->user()->id);
        }

        $harvests = $query->latest()->paginate(15);

        return Inertia::render('Harvests/Index', [
            'harvests' => $harvests,
        ]);
    }

    public function create()
    {
        $plots = Auth::user()->farmerProfile->plots ?? collect();
        $timeWindows = BidTimeWindow::all();
        $categories = HarvestCategory::all();

        return Inertia::render('Harvests/Create', [
            'plots' => $plots,
            'timeWindows' => $timeWindows,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'harvest_date' => 'required|date',
            'total_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'bid_time_window_id' => 'nullable|exists:bid_time_windows,id',
            'bid_start_time' => 'nullable|date',
        ]);

        $harvest = Harvest::create([
            'farmer_id' => $request->user()->id,
            'status' => $validated['bid_start_time'] ? 'scheduled' : 'scheduled',
            ...$validated,
        ]);

        return redirect()->route('harvests.show', $harvest)->with('success', 'Harvest created successfully');
    }

    public function show(Harvest $harvest)
    {
        $harvest->load(['plot', 'farmer.farmerProfile', 'bids.buyer', 'bidTimeWindow']);

        $user = Auth::user();
        $canBid = $user->user_type === 'buyer' && $harvest->isActive();

        return Inertia::render('Harvests/Show', [
            'harvest' => $harvest,
            'canBid' => $canBid,
            'categories' => HarvestCategory::all(),
        ]);
    }

    public function startBid(Request $request, Harvest $harvest)
    {
        $this->authorize('update', $harvest);

        $validated = $request->validate([
            'bid_time_window_id' => 'required|exists:bid_time_windows,id',
        ]);

        $timeWindow = BidTimeWindow::findOrFail($validated['bid_time_window_id']);

        $harvest->update([
            'status' => 'active',
            'bid_time_window_id' => $timeWindow->id,
            'bid_start_time' => now(),
            'bid_end_time' => now()->addMinutes($timeWindow->duration_minutes),
        ]);

        return redirect()->route('harvests.show', $harvest)->with('success', 'Bidding started successfully');
    }

    public function selectWinner(Request $request, Harvest $harvest)
    {
        $this->authorize('update', $harvest);

        $validated = $request->validate([
            'bid_id' => 'required|exists:harvest_bids,id',
        ]);

        $bid = HarvestBid::findOrFail($validated['bid_id']);

        $harvest->update([
            'status' => 'completed',
            'winning_bid_id' => $bid->id,
            'winner_id' => $bid->buyer_id,
        ]);

        // Update bid statuses
        $harvest->bids()->where('id', '!=', $bid->id)->update(['status' => 'lost']);
        $bid->update(['status' => 'won']);

        return redirect()->route('harvests.show', $harvest)->with('success', 'Winner selected successfully');
    }
}

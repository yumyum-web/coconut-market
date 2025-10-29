<?php

namespace App\Http\Controllers;

use App\Models\Byproduct;
use App\Models\Harvest;
use App\Models\HarvestBid;
use App\Models\Plot;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isFarmer()) {
            return $this->farmerDashboard($user);
        }

        return $this->buyerDashboard($user);
    }

    private function farmerDashboard($user)
    {
        $plots = Plot::where('farmer_id', $user->id)->count();
        $activeHarvests = Harvest::where('farmer_id', $user->id)
            ->where('status', 'active')
            ->with(['bids', 'plot'])
            ->get();
        $scheduledHarvests = Harvest::where('farmer_id', $user->id)
            ->where('status', 'scheduled')
            ->with('plot')
            ->orderBy('harvest_date')
            ->take(5)
            ->get();
        $products = Product::where('farmer_id', $user->id)
            ->where('status', 'available')
            ->count();
        $byproducts = Byproduct::where('farmer_id', $user->id)
            ->where('status', 'available')
            ->count();

        return Inertia::render('Dashboard/FarmerDashboard', [
            'stats' => [
                'plots' => $plots,
                'activeHarvests' => $activeHarvests->count(),
                'products' => $products,
                'byproducts' => $byproducts,
            ],
            'activeHarvests' => $activeHarvests,
            'scheduledHarvests' => $scheduledHarvests,
        ]);
    }

    private function buyerDashboard($user)
    {
        $activeBids = HarvestBid::where('buyer_id', $user->id)
            ->where('status', 'pending')
            ->with(['harvest.plot', 'harvest.farmer'])
            ->get();
        $wonBids = HarvestBid::where('buyer_id', $user->id)
            ->where('status', 'won')
            ->with(['harvest.plot', 'harvest.farmer'])
            ->latest()
            ->take(5)
            ->get();
        $activeHarvests = Harvest::where('status', 'active')
            ->where('bid_end_time', '>', now())
            ->with(['plot', 'farmer'])
            ->orderBy('bid_end_time')
            ->take(10)
            ->get();

        return Inertia::render('Dashboard/BuyerDashboard', [
            'stats' => [
                'activeBids' => $activeBids->count(),
                'wonBids' => $wonBids->count(),
            ],
            'activeBids' => $activeBids,
            'wonBids' => $wonBids,
            'activeHarvests' => $activeHarvests,
        ]);
    }
}

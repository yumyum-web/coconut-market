<?php

use App\Http\Controllers\ByproductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HarvestBidController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Plots (Farmer only)
    Route::middleware('can:isFarmer,App\Models\User')->group(function () {
        Route::resource('plots', PlotController::class);
    });

    // Harvests
    Route::get('/harvests', [HarvestController::class, 'index'])->name('harvests.index');
    Route::get('/harvests/create', [HarvestController::class, 'create'])->name('harvests.create');
    Route::post('/harvests', [HarvestController::class, 'store'])->name('harvests.store');
    Route::get('/harvests/{harvest}', [HarvestController::class, 'show'])->name('harvests.show');
    Route::post('/harvests/{harvest}/start-bid', [HarvestController::class, 'startBid'])->name('harvests.start-bid');
    Route::post('/harvests/{harvest}/select-winner', [HarvestController::class, 'selectWinner'])->name('harvests.select-winner');

    // Harvest Bids (Buyer only)
    Route::middleware('can:isBuyer,App\Models\User')->group(function () {
        Route::post('/harvests/{harvest}/bids', [HarvestBidController::class, 'store'])->name('harvest-bids.store');
    });
    Route::get('/harvest-bids', [HarvestBidController::class, 'index'])->name('harvest-bids.index');

    // Products (Farmer only)
    Route::middleware('can:isFarmer,App\Models\User')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Byproducts (Farmer only)
    Route::middleware('can:isFarmer,App\Models\User')->group(function () {
        Route::resource('byproducts', ByproductController::class);
    });

    // Ratings
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
});

require __DIR__.'/settings.php';

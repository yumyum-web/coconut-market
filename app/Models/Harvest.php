<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Harvest extends Model
{
    protected $fillable = [
        'plot_id',
        'farmer_id',
        'harvest_date',
        'total_quantity',
        'notes',
        'status',
        'bid_time_window_id',
        'bid_start_time',
        'bid_end_time',
        'winning_bid_id',
        'winner_id',
    ];

    protected function casts(): array
    {
        return [
            'harvest_date' => 'date',
            'total_quantity' => 'integer',
            'bid_start_time' => 'datetime',
            'bid_end_time' => 'datetime',
        ];
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function bidTimeWindow(): BelongsTo
    {
        return $this->belongsTo(BidTimeWindow::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(HarvestBid::class);
    }

    public function winningBid(): BelongsTo
    {
        return $this->belongsTo(HarvestBid::class, 'winning_bid_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
               $this->bid_end_time > now();
    }
}

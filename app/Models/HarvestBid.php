<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestBid extends Model
{
    protected $fillable = [
        'harvest_id',
        'buyer_id',
        'category_bids',
        'total_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'category_bids' => 'array',
            'total_amount' => 'decimal:2',
        ];
    }

    public function harvest(): BelongsTo
    {
        return $this->belongsTo(Harvest::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}

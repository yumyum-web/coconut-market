<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBid extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'bid_amount',
        'quantity',
        'status',
        'notes',
        'bid_end_time',
    ];

    protected function casts(): array
    {
        return [
            'bid_amount' => 'decimal:2',
            'quantity' => 'decimal:2',
            'bid_end_time' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}

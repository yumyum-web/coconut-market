<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Byproduct extends Model
{
    protected $fillable = [
        'farmer_id',
        'name',
        'description',
        'quantity',
        'unit',
        'price_per_unit',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(ByproductBid::class);
    }
}

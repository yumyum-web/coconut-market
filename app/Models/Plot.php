<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plot extends Model
{
    protected $fillable = [
        'farmer_id',
        'name',
        'description',
        'size',
        'location',
        'tree_count',
        'potential_harvest',
        'harvest_frequency',
        'custom_frequency',
        'is_harvest_sold',
        'can_deliver',
        'available_categories',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'decimal:2',
            'tree_count' => 'integer',
            'potential_harvest' => 'integer',
            'is_harvest_sold' => 'boolean',
            'can_deliver' => 'boolean',
            'available_categories' => 'array',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PlotImage::class)->orderBy('order');
    }

    public function harvests(): HasMany
    {
        return $this->hasMany(Harvest::class);
    }
}

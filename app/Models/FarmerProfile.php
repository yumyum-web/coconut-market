<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'farm_name',
        'farm_size',
        'years_experience',
        'location',
        'average_rating',
        'total_ratings',
    ];

    protected function casts(): array
    {
        return [
            'farm_size' => 'decimal:2',
            'average_rating' => 'decimal:2',
            'years_experience' => 'integer',
            'total_ratings' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

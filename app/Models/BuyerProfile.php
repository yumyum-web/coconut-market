<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'company_name',
        'business_type',
        'average_rating',
        'total_ratings',
    ];

    protected function casts(): array
    {
        return [
            'average_rating' => 'decimal:2',
            'total_ratings' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

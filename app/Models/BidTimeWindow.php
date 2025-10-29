<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidTimeWindow extends Model
{
    protected $fillable = [
        'name',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
        ];
    }
}

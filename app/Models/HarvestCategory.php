<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HarvestCategory extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'description',
    ];
}

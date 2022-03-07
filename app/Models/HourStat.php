<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourStat extends Model
{
    use HasFactory;

    protected $fillable = [
        "date", "hour", "opening_price", "closing_price", "maximum_price", "minimum_price", "volume", "stock",
    ];
}

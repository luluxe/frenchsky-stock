<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        "stock", "type", "owner", "price", "quantity"
    ];

    // Util

    public function isSell() {
        return $this->type == "SELL";
    }

    public function isBuy() {
        return $this->type == "BUY";
    }
}

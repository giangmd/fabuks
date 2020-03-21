<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeHistory extends Model
{
    protected $fillable = [
        'user_id', 'from', 'to', 'amount', 'price_order', 'price_done', 'status',
    ];

    public function user()
    {
        return $this->beLongsTo(User::class);
    }
}

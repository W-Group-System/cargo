<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessedOrders extends Model
{
    protected $table = "processed_orders";
    protected $primaryKey = 'id';
    protected $fillable = [
        "SapServer",
        "CardCode",
        "CardName",
        "MinDocDate"
    ];

    public function OrderData()
    {
        return $this->hasOne(Order::class, 'process_order_id', 'id');
    }
}

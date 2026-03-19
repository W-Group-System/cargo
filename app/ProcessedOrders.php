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
}

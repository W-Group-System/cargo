<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use SoftDeletes;
    
    protected $table = "order_items";
    protected $fillable = [
        'order_id',
        'CardCode',
        'ItemCode',
        'Dscription',
        'Quantity',
    ];
}

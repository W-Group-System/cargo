<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class OrderItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = "order_items";
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'CardCode',
        'ItemCode',
        'Dscription',
        'Quantity',
    ];

    public function Orders()
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}

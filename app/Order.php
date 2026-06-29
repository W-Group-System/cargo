<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $primaryKey = 'id';
    protected $fillable = [
        'process_order_id',
        'sap_server',
        'DocNum',
        'CardCode',
        'CardName',
        'Label',
        'Packaging',
        'BuyersPO',
        'ContactName',
        'LoadingPort',
        'PortOfDestination'
    ];

    public function ProcessedOrders()
    {
        return $this->belongsTo(ProcessedOrders::class, 'process_order_id', 'id');
    }

    public function OrderItemList()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}

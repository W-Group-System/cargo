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
        "MinDocDate",
        "AvailabilityDate",
        "PickupDate",
        "CargoStatus",
        "OrderStatus",
        "ShipmentStatus",
        "is_coload",
        "coloaded_by",
        "coload_order",
    ];

    public function OrderData()
    {
        return $this->hasOne(Order::class, 'process_order_id', 'id');
    }
    public function CargoStatus()
    {
        return $this->hasOne(CargoStatus::class, 'code', 'CargoStatus');
    }
    public function ProcessedOrderStatus()
    {
        return $this->hasOne(ProcessedOrderStatus::class, 'code', 'OrderStatus');
    }
    public function ShipmentStatus()
    {
        return $this->hasOne(ShipmentStatus::class, 'code', 'ShipmentStatus');
    }
}

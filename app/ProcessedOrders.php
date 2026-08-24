<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProcessedOrders extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
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
        "cargo_posting_date",
        "cbw_doc_status",
        "date_loaded"
    ];

    public function OrderData()
    {
        return $this->hasMany(Order::class, 'process_order_id', 'id');
    }
    public function CargoStatus()
    {
        return $this->hasOne(CargoStatus::class, 'code', 'CargoStatus');
    }
    public function ProcessedOrderStatus()
    {
        return $this->hasOne(ProcessedOrderStatus::class, 'code', 'OrderStatus');
    }
    public function ShipmentDetails()
    {
        return $this->hasOne(ShipmentDetails::class, 'process_order_id', 'id');
    }
    public function ShipmentFiles()
    {
        return $this->hasMany(ShipmentFiles::class, 'processed_order_id', 'id');
    }
    public function Warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse', 'SapServer');
    }
}

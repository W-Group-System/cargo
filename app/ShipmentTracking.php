<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $table = 'shipment_tracking';
    protected $primaryKey = 'id';
    protected $fillable = [
        'shipment_details_id',
        'tracking_point',
        'arrival_date',
        'status',
        'remarks'
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function ShipmentDetails()
    {
        return $this->belongsTo(ShipmentDetails::class, 'id','shipment_details_id');
    }
    public function TrackingPoint()
    {
        return $this->HasOne(TrackingPoints::class, 'code','tracking_point');
    }
    public function DeliveryStatus()
    {
        return $this->HasOne(DeliveryStatus::class, 'code','status');
    }
}

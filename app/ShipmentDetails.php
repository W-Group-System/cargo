<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ShipmentDetails extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'shipment_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'process_order_id',
        'delivery_status',
        'tracking_points',
        'location',
        'invoice_number',
        'mode',
        'cbw_doc_status',
        'pallet_type',
        'cargo_readiness_date',
        'posting_date',
        'current_location',
        'region',
        'shipping_line',
        'ed_bl_number',
        'container_number',
        'courier_tracking',
        'etd_origin',
        'atd_origin',
        'eta_destination',
        'ata_destination',
        'delivery_date',
        'date_docs_completed',
        'remarks',
        'email_recipients',
        'cc_recipients',
        'vessel_name',
        'atp_date',
        'dt_date',
        'notification_enabled'
    ];

    public function ProcessedOrder()
    {
        return $this->belongsTo(ProcessedOrders::class,'process_order_id','id');
    }
    public function ShipmentTracking()
    {
        return $this->hasMany(ShipmentTracking::class, 'shipment_details_id', 'id');
    }
    public function DeliveryStatus()
    {
        return $this->hasMany(DeliveryStatus::class, 'code', 'delivery_status');
    }
    public function DelayedShipmentUpdate()
    {
        return $this->hasOne(DelayedShipmentUpdate::class, 'shipment_details_id', 'id');
    }
}

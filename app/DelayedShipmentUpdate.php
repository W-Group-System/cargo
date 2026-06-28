<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelayedShipmentUpdate extends Model
{
    protected $table = 'delayed_shipment_updates';
    protected $primaryKey = 'id';
    protected $fillable = [
        'shipment_details_id',
        'prev_eta',
        'updated_eta',
        'is_notif_sent'
    ];
}

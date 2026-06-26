<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentFiles extends Model
{
    protected $table = 'shipment_files';
    protected $primaryKey = 'id';
    protected $fillable = [
        'processed_order_id',
        'file_name',
        'shipment_status',
        'file_type',
        'file_ext',
        'file_path',
        'user_id'
    ];
}

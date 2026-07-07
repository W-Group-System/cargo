<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ShipmentFiles extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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

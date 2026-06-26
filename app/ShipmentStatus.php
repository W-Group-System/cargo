<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentStatus extends Model
{
    protected $table = "shipment_status";
    protected $primaryKey = 'id';
    protected $fillable = [
        "code",
        "description"
    ];

    public static function ShipmentStatusArray(){
        return self::pluck('description', 'code');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    protected $table = 'delivery_status';
    protected $primaryKey = 'id';
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}

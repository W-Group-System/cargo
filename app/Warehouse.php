<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouse';
    protected $primaryKey = 'id';
    protected $fillable = [
        'warehouse',
        'description'
    ];
}

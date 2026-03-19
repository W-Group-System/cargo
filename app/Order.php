<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'process_order_id',
        'sap_server',
        'DocNum',
        'CardCode',
        'CardName',
        'Label',
        'Packaging',
    ];
}

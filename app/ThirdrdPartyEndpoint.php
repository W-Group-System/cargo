<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdrdPartyEndpoint extends Model
{
    protected $table = '3rd_party_endpoint';
    protected $primaryKey = 'id';
    protected $fillable = [
        'Name',
        'Code',
        'Endpoint',
        'ApiKey'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'module_name',
        'is_header',
        'header_id',
        'module_url',
        'module_route',
        'module_order',
        'header_order',
        'icon'
    ];

    public function RolesAccess()
    {
        return $this->hasMany(RolesAccess::class, 'module_id', 'id');
    }
    
}

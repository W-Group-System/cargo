<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolesAccess extends Model
{
    protected $table = 'roles_access';
    protected $primaryKey = 'id';
    protected $fillable = [
        'role_id',
        'module_id',
        'can_create',
        'can_read',
        'can_update',
        'can_delete'
    ];

    public function Roles()
    {
        return $this->belongsTo(Roles::class, 'id', 'role_id');
    }
    public function Modules()
    {
        return $this->belongsTo(Modules::class, 'id', 'module_id');
    }
    public function RolesModules()
    {
        return $this->belongsTo(RolesAccess::class, 'module_id', 'module_id');
    }
}

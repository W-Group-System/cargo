<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'description',
        'subject',
        'header',
        'footer',
        'content'
    ];
}

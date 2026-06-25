<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationLogs extends Model
{
    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'shipment_details_id',
        'template_code',
        'user_id',
        'subject',
        'content',
        'is_sent',
        'shipment_details_id'
    ];
}

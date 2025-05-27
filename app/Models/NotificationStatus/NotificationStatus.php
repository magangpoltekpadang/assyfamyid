<?php

namespace App\Models\NotificationStatus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationStatus extends Model
{
    use HasFactory;

    protected $table = 'notification_statuses';
    protected $primaryKey = 'status_id';

    protected $fillable = [
        'status_name',
        'code',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
}

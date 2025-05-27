<?php

namespace App\Models\NotificationType;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationType extends Model
{
    use HasFactory;

    protected $table = 'notification_types';
    protected $primaryKey = 'notification_type_id';

    protected $fillable = [
        'type_name',
        'code',
        'template_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
}

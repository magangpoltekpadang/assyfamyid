<?php

namespace App\Models\PaymentStatus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_statuses';
    protected $primaryKey = 'payment_status_id'; 

    protected $fillable = ['status_name', 'code', 'description'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

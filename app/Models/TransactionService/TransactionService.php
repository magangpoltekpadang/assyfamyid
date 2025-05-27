<?php

namespace App\Models\TransactionService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionService extends Model
{
    use HasFactory;

    protected $table = 'transaction_services';
    protected $primaryKey = 'transaction_service_id';

    protected $fillable = [
        'transaction_id',
        'service_id',
        'quantity',
        'unit_price', 
        'discount',
        'total_price',
        'worker_id',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(\App\Models\Service\Service::class);
    }

    public function transaction()
    {
        return $this->belongsTo(\App\Models\Transaction\Transaction::class);
    }
}

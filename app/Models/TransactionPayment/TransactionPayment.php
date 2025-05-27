<?php

namespace App\Models\TransactionPayment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionPayment extends Model
{
    use HasFactory;

    protected $table = 'transaction_payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'reference_number',
        'status_id',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->created_at = $model->updated_at = now();
        });

        static::updating(function ($model) {

            $model->updated_at = now();
        });
    }
}

<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
     use HasFactory;

    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'transaction_code',
        'customer_id',
        'outlet_id',
        'staff_id',
        'shift_id',
        'transaction_date',
        'subtotal',
        'discount',
        'tax',
        'final_price',
        'payment_status_id',
        'gate_opened',
        'receipt_printed',
        'whatsapp_sent',
        'notes',
    ];

    protected $casts = [
        'transaction_date'  => 'datetime',
        'subtotal'          => 'decimal:2',
        'discount'          => 'decimal:2',
        'tax'               => 'decimal:2',
        'final_price'       => 'decimal:2',
        'gate_opened'       => 'boolean',
        'receipt_printed'   => 'boolean',
        'whatsapp_sent'     => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];


    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer\Customer::class, 'customer_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class, 'outlet_id');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift\Shift::class, 'shift_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(\App\Models\PaymentStatus\PaymentStatus::class, 'payment_status_id');
    }

    public function transactionServices()
    {
        return $this->hasMany(\App\Models\TransactionService\TransactionService::class, 'transaction_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\TransactionPayment\TransactionPayment::class, 'transaction_id');
    }


}

<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';

    protected $fillable = ['customer_name', 'outlet_id', 'staff_id', 'transaction_date', 'total_price', 'status'];


    // Tipe data untuk beberapa kolom
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

        public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class);
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class);
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift\Shift::class);
    }

    public function transactionServices()
    {
        return $this->hasMany(\App\Models\TransactionService\TransactionService::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\TransactionPayment\TransactionPayment::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer\Customer::class);
    }


}

<?php

namespace App\Models\MembershipTransaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipTransaction extends Model
{
    use HasFactory;

    protected $table = 'member_transactions';
    protected $primaryKey = 'member_transaction_id';

    protected $fillable = [
        'customer_id',
        'package_id',
        'outlet_id',
        'staff_id',
        'transaction_date',
        'expiry_date',
        'price',
        'payment_method_id',
        'receipt_number',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer\Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\MembershipPackage\MembershipPackage::class, 'package_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class);
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(\App\Models\PaymentMethod\PaymentMethod::class, 'payment_method_id');
    }
}

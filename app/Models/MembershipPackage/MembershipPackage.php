<?php

namespace App\Models\MembershipPackage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MembershipTransaction\MembershipTransaction;

class MembershipPackage extends Model
{
    use HasFactory;

    protected $table = 'membership_packages';
    protected $primaryKey = 'package_id';

    protected $fillable = [
        'package_name',
        'duration_days',
        'price',
        'max_vehicles',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'max_vehicles' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function membershipTransactions()
    {
        return $this->belongsTo(\App\Models\MembershipTransaction\MembershipTransaction::class, 'package_id');
    }
}

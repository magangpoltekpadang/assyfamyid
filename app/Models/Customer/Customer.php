<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'plate_number',
        'name',
        'phone_number',
        'vehicle_type_id',
        'vehicle_color',
        'member_number',
        'join_date',
        'member_expiry_date',
        'is_member',
    ];

    protected $casts = [
        'join_date' => 'datetime',
        'member_expiry_date' => 'datetime',
        'is_member' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(\App\Models\VehicleTyp\VehicleType::class, 'vehicle_type_id', 'vehicle_type_id');
    }

}

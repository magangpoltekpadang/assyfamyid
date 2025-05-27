<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $primaryKey = 'service_id';
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'service_name',
        'service_type_id',
        'price',
        'estimated_duration',
        'description',
        'is_active',
        'outlet_id',

    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(\App\Models\ServiceType\ServiceType::class, 'service_type_id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class);
    }
}

<?php

namespace App\Models\Outlet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'outlets';
    protected $primaryKey = 'outlet_id';

    protected $fillable = [
        'outlet_name',
        'address',
        'phone_number',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

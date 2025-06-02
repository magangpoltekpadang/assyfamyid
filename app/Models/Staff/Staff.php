<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password_hash',
        'outlet_id',
        'role_id',
        'is_active',
        'last_login',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class, 'outlet_id', 'outlet_id');
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role\Role::class, 'role_id', 'role_id');
    }

}




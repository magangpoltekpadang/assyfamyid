<?php

namespace App\Models\VehicleTyp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $primaryKey = 'vehicle_type_id';

    protected $fillable = [
        'type_name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeFilter($query, $args)
    {
        if (isset($args['search'])) {
            $query->where('type_name', 'like', '%' . $args['search'] . '%')
                ->orWhere('code', 'like', '%' . $args['search'] . '%')
                ->orWhere('description', 'like', '%' . $args['search'] . '%');
        }

        if (isset($args['is_active'])) {
            $query->where('is_active', $args['is_active']);
        }

        return $query;
    }

}

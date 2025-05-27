<?php

namespace App\Models\Shift;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';
    public $timestamps = true;

    protected $fillable = ['shift_name', 'start_time', 'end_time', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class);
    }

}

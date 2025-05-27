<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $primaryKey = 'expense_id';

    protected $fillable = [
        'expense_code',
        'outlet_id',
        'expense_date',
        'amount',
        'category',
        'description',
        'staff_id',
        'shift_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class);
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet\Outlet::class);
    }
}

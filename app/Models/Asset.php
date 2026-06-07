<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'category',
        'brand',
        'model',
        'serial_number',
        'specification',
        'purchase_date',
        'purchase_price',
        'condition',
        'status',
        'location',
        'assigned_to_user_id',
    ];

    protected function casts(): array
    {
        return [
            'specification' => 'array',
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // asset dipakai oleh user
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    // asset memiliki banyak ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // asset memiliki banyak repair log
    public function repairLogs()
    {
        return $this->hasMany(RepairLog::class);
    }
}
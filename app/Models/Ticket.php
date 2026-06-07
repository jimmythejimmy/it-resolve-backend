<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'asset_id',
        'reported_by',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'reported_at',
        'resolved_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // ticket milik asset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // pelapor ticket
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // teknisi penanggung jawab
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // histori repair
    public function repairLogs()
    {
        return $this->hasMany(RepairLog::class);
    }
}
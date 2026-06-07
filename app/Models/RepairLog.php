<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'asset_id',
        'actor_user_id',
        'from_status',
        'to_status',
        'action_type',
        'notes',
        'metadata',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'logged_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Ticket extends Model
{
    protected $fillable = [
        'title', 'description', 'status', 'priority', 'created_by', 'assigned_to',  'hash_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->hash_id = (string) Str::uuid(); 
        });
    }

    public function getRouteKeyName()
    {
        return 'hash_id';
    }
}

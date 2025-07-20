<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbgeCity extends Model
{
    /** @use HasFactory<\Database\Factories\IbgeCityFactory> */
    use HasFactory;


    public function state()
    {
        return $this->belongsTo(IbgeState::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IbgeState extends Model
{
    /** @use HasFactory<\Database\Factories\IbgeStateFactory> */
    use HasFactory;


    public function cities()
    {
        return $this->hasMany(IbgeCity::class);
    }
}

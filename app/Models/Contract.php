<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(User::class, 'travel_agent_id');
    }
}

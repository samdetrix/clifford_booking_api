<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'accommodation_id',
        'travel_agent_id',
        'contract_rate',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(User::class, 'travel_agent_id');
    }

    
}

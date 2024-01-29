<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id',
        'guest_id',
        'check_in_date',
        'check_out_date',
        'status',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }
}

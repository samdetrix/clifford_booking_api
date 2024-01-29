<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contract;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'standard_rack_rate',
        'status',
        'capacity',
        'is_wifi_available',
        'is_parking_available',
        'amenities',
        'created_by',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
    
}

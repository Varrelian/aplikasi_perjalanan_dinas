<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cost_center_id',
        'origin',
        'origin_code',
        'destination',
        'dest_code',
        'departure_date',
        'return_date',
        'purpose',
        'total_cost',
        'approval_stage',
        'policy_status',
    ];

    public function traveler()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'departure_date' => 'date',
        'return_date'    => 'date',
        'flight_cost'    => 'decimal:2',
        'hotel_cost'     => 'decimal:2',
        'transit_cost'   => 'decimal:2',
        'total_cost'     => 'decimal:2',
        'submitted_at'   => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($trip) {
            $nextSeq = (static::max('id') ?? 0) + 1;
            if (empty($trip->request_code)) {
                $trip->request_code = 'TRV-' . (10000 + $nextSeq);
            }
            if (empty($trip->trip_id)) {
                $trip->trip_id = 'TRIP-' . (10000 + $nextSeq);
            }
            if (empty($trip->cost_center) && !empty($trip->cost_center_id)) {
                $trip->cost_center = $trip->cost_center_id;
            }
            if (empty($trip->cost_center)) {
                $trip->cost_center = 'CC-402';
            }
            if (empty($trip->currency)) {
                $trip->currency = 'IDR';
            }
            if (empty($trip->approval_stage)) {
                $trip->approval_stage = 'Line Manager Review';
            }
            if (empty($trip->overall_status)) {
                $trip->overall_status = 'pending_approval';
            }
            if (empty($trip->policy_status)) {
                $trip->policy_status = 'compliant';
            }
            if (empty($trip->budget_status)) {
                $trip->budget_status = 'available';
            }
            if (empty($trip->departure_time_slot)) {
                $trip->departure_time_slot = 'Morning Flight (06:00 - 11:00)';
            }
            if (empty($trip->return_time_slot)) {
                $trip->return_time_slot = 'Evening Flight (17:00 - 22:00)';
            }
            if (empty($trip->purpose_type)) {
                $trip->purpose_type = 'Client Meeting';
            }
            if (empty($trip->submitted_at)) {
                $trip->submitted_at = now();
            }
        });
    }

    public function getPurposeAttribute()
    {
        return $this->purpose_title ?? $this->purpose_description ?? '';
    }

    public function setPurposeAttribute($value)
    {
        $this->attributes['purpose_title'] = \Illuminate\Support\Str::limit($value, 100);
        $this->attributes['purpose_description'] = $value;
    }

    public function getCostCenterIdAttribute()
    {
        return $this->cost_center ?? $this->attributes['cost_center'] ?? null;
    }

    public function setCostCenterIdAttribute($value)
    {
        $this->attributes['cost_center'] = $value;
    }

    public function traveler()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

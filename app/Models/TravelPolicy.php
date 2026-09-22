<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPolicy extends Model
{
    use HasFactory;

    protected $table = 'travel_policies';

    protected $fillable = [
        'name',
        'rule_type',
        'scope',
        'applicable_band',
        'amount_limit',
        'currency',
        'allowed_class',
        'description',
        'is_active',
    ];

    protected $casts = [
        'amount_limit' => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    /**
     * Scope for active policies only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

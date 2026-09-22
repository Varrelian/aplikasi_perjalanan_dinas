<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentBudget extends Model
{
    use HasFactory;

    protected $table = 'department_budgets';

    protected $fillable = [
        'department_name',
        'cost_center',
        'fiscal_year',
        'budget_amount',
        'spent_amount',
        'currency',
    ];

    protected $casts = [
        'budget_amount'        => 'decimal:2',
        'spent_amount'         => 'decimal:2',
        'fiscal_year'          => 'integer',
        'utilized_percentage'  => 'float',
    ];

    /**
     * Get remaining available budget in IDR
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float)$this->budget_amount - (float)$this->spent_amount);
    }

    /**
     * Get calculated utilization percentage
     */
    public function getCalculatedUtilizationAttribute(): float
    {
        if ((float)$this->budget_amount <= 0) return 0;
        return round(((float)$this->spent_amount / (float)$this->budget_amount) * 100, 1);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\TravelRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the executive travel command center dashboard.
     *
     * Aggregates active authorizations, pending approval queues,
     * departmental budget encumbrances, and policy compliance rates.
     */
    public function index(Request $request): View|JsonResponse
    {
        // 1. Core Executive KPIs
        $totalTripsCount = TravelRequest::count();

        $activeTripsCount = TravelRequest::where(function ($query) {
            $query->where('approval_stage', 'like', '%Issued%')
                  ->orWhere('approval_stage', 'like', '%Confirmed%')
                  ->orWhere('approval_stage', 'like', '%Line Manager%')
                  ->orWhere('approval_stage', 'like', '%Finance%')
                  ->orWhere('approval_stage', 'like', '%Director%');
        })->count();

        $pendingApprovalsCount = TravelRequest::where(function ($query) {
            $query->where('approval_stage', 'like', '%Pending%')
                  ->orWhere('approval_stage', 'like', '%Review%')
                  ->orWhere('approval_stage', 'like', '%Manager%');
        })->count();

        // 2. Budget & Department Spending Calculations
        $totalCommittedCost = TravelRequest::where('approval_stage', '!=', 'Rejected')->sum('total_cost');

        $compliantCount = TravelRequest::where('policy_status', 'compliant')->count();
        $policyComplianceRate = $totalTripsCount > 0
            ? round(($compliantCount / $totalTripsCount) * 100, 1)
            : 98.4;

        // 3. Spotlight Trip (Active Authorization Highlight)
        $spotlightTrip = TravelRequest::with('traveler')
            ->where('approval_stage', 'like', '%Ticket Issued%')
            ->orWhere('approval_stage', 'like', '%Confirmed%')
            ->latest()
            ->first() ?? TravelRequest::with('traveler')->latest()->first();

        // 4. Recent Travel Requests Queue
        $recentTrips = TravelRequest::with('traveler')
            ->latest()
            ->take(8)
            ->get();

        // Support AJAX/JSON API consumption if requested
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'metrics' => [
                    'active_trips'           => $activeTripsCount,
                    'pending_approvals'      => $pendingApprovalsCount,
                    'total_trips'            => $totalTripsCount,
                    'total_committed_cost'   => $totalCommittedCost,
                    'policy_compliance_rate' => $policyComplianceRate,
                ],
                'spotlight_trip' => $spotlightTrip,
                'recent_trips'   => $recentTrips,
            ]);
        }

        return view('dashboard', compact(
            'activeTripsCount',
            'pendingApprovalsCount',
            'totalTripsCount',
            'totalCommittedCost',
            'policyComplianceRate',
            'spotlightTrip',
            'recentTrips'
        ));
    }

    /**
     * Return live KPI metrics for dashboard polling or asynchronous refresh.
     */
    public function metrics(): JsonResponse
    {
        $activeTrips = TravelRequest::whereIn('approval_stage', [
            'Ticket Issued / Confirmed',
            'Manager Review',
            'Finance Review'
        ])->count();

        $pendingApprovals = TravelRequest::where('approval_stage', 'like', '%Pending%')
            ->orWhere('approval_stage', 'like', '%Review%')
            ->count();

        $quarterlyBudgetUsed = TravelRequest::sum('total_cost');

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'active_authorizations' => $activeTrips,
                'pending_decisions'     => $pendingApprovals,
                'budget_encumbered_idr' => $quarterlyBudgetUsed,
                'sla_alert_count'       => TravelRequest::where('policy_status', 'flagged')->count(),
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\TravelRequest;

class ApprovalController extends Controller
{
    /**
     * Display the Approvals & Decision Center queue.
     *
     * Supports filtering by all pending requests, flagged policy exceptions,
     * and high-value requisitions exceeding Rp 10.000.000.
     */
    public function index(Request $request): View|JsonResponse
    {
        $filter = $request->query('filter', 'all');

        // Base query for pending authorization items
        $baseQuery = TravelRequest::with('traveler')
            ->where(function ($q) {
                $q->where('approval_stage', 'like', '%Pending%')
                  ->orWhere('approval_stage', 'like', '%Review%')
                  ->orWhere('approval_stage', 'like', '%Manager%')
                  ->orWhere('approval_stage', 'like', '%Director%');
            });

        // Compute queue counters for top indicator tabs
        $pendingApprovalsCount = (clone $baseQuery)->count();
        $flaggedCount = (clone $baseQuery)->where('policy_status', '!=', 'compliant')->count();
        $highValueCount = (clone $baseQuery)->where('total_cost', '>=', 10000000)->count();

        // Apply active filter
        $query = clone $baseQuery;

        if ($filter === 'flagged') {
            $query->where('policy_status', '!=', 'compliant');
        } elseif ($filter === 'high_value') {
            $query->where('total_cost', '>=', 10000000);
        }

        $pendingRequests = $query->latest()->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'filter'  => $filter,
                'counts'  => [
                    'pending'    => $pendingApprovalsCount,
                    'flagged'    => $flaggedCount,
                    'high_value' => $highValueCount,
                ],
                'data'    => $pendingRequests,
            ]);
        }

        return view('approvals.index', compact(
            'pendingRequests',
            'pendingApprovalsCount',
            'flaggedCount',
            'highValueCount',
            'filter'
        ));
    }

    /**
     * Display the specified travel authorization for approval review.
     */
    public function show(string $id): View
    {
        $trip = TravelRequest::with('traveler')->findOrFail($id);

        return view('trips.show', [
            'trip'           => $trip,
            'isApprovalView' => true,
        ]);
    }

    /**
     * Record an executive authorization decision (Authorize, Decline, or Request Clarification).
     */
    public function recordDecision(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|string|in:approve,reject,clarification,request_clarification',
            'reason' => 'nullable|string|max:1000',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $trip = TravelRequest::with('traveler')->findOrFail($id);
        $decision = $validated['action'];
        $notes = $validated['reason'] ?? $validated['notes'] ?? null;

        if ($decision === 'approve') {
            // Sequential clearance progression
            if (str_contains($trip->approval_stage, 'Manager')) {
                $trip->approval_stage = 'Finance Review';
                $message = "Travel Request #{$trip->id} authorized by Line Manager. Forwarded to Finance.";
            } elseif (str_contains($trip->approval_stage, 'Finance')) {
                $trip->approval_stage = 'Director SLA Review';
                $message = "Travel Request #{$trip->id} authorized by Finance. Forwarded to Director.";
            } else {
                $trip->approval_stage = 'Ticket Issued / Confirmed';
                $message = "Travel Request #{$trip->id} granted final authorization. Booking clearance issued.";
            }
        } elseif ($decision === 'reject') {
            $trip->approval_stage = 'Rejected';
            $message = "Travel Request #{$trip->id} declined." . ($notes ? " Reason: {$notes}" : "");
        } else {
            $trip->approval_stage = 'Clarification Requested';
            $message = "Clarification requested from traveler regarding Travel Request #{$trip->id}.";
        }

        $trip->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'trip'    => $trip,
            ]);
        }

        return redirect()->route('approvals.index')
                         ->with('success', $message);
    }

    /**
     * Perform batch authorization on standard, policy-compliant travel requests.
     */
    public function bulkApprove(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'trip_ids'   => 'required|array|min:1',
            'trip_ids.*' => 'required|string',
        ]);

        $updatedCount = TravelRequest::whereIn('id', $validated['trip_ids'])
            ->where('policy_status', 'compliant')
            ->update(['approval_stage' => 'Finance Review']);

        $message = "Successfully bulk-approved {$updatedCount} compliant travel requests.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count'   => $updatedCount,
                'message' => $message,
            ]);
        }

        return redirect()->route('approvals.index')->with('success', $message);
    }
}

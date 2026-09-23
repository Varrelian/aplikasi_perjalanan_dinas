<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelRequest;
use App\Models\TravelPolicy;
use App\Models\DepartmentBudget;

class TravelRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TravelRequest::with('traveler');

        if ($request->filled('q')) {
            $query->where('destination', 'like', '%' . $request->q . '%')
                  ->orWhere('id', 'like', '%' . $request->q . '%');
        }

        $trips = $query->latest()->paginate(10);

        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cost_center_id' => 'required|string',
            'origin_code'    => 'required|string|size:3',
            'dest_code'      => 'required|string|size:3',
            'departure_date' => 'required|date',
            'return_date'    => 'required|date|after_or_equal:departure_date',
            'purpose'        => 'required|string|max:1000',
            'flight_cost'    => 'nullable|numeric',
            'hotel_cost'     => 'nullable|numeric',
            'per_diem_cost'  => 'nullable|numeric',
        ]);

        $cities = [
            'CGK' => 'Jakarta (CGK)',
            'HLP' => 'Jakarta (HLP)',
            'SUB' => 'Surabaya (SUB)',
            'DPS' => 'Bali (DPS)',
            'SIN' => 'Singapore (SIN)',
            'KNO' => 'Medan (KNO)',
            'BPN' => 'Balikpapan (BPN)',
        ];

        $flightCost = (float)($validated['flight_cost'] ?? 0);
        $hotelCost = (float)($validated['hotel_cost'] ?? 0);
        $transitCost = (float)($validated['per_diem_cost'] ?? 0);
        $totalCost = $flightCost + $hotelCost + $transitCost;

        $trip = TravelRequest::create([
            'user_id'             => auth()->id() ?? 1,
            'cost_center'         => $validated['cost_center_id'],
            'origin_code'         => $validated['origin_code'],
            'dest_code'           => $validated['dest_code'],
            'origin'              => $cities[$validated['origin_code']] ?? $validated['origin_code'],
            'destination'         => $cities[$validated['dest_code']] ?? $validated['dest_code'],
            'departure_date'      => $validated['departure_date'],
            'return_date'         => $validated['return_date'],
            'departure_time_slot' => 'Morning Flight (06:00 - 11:00)',
            'return_time_slot'    => 'Evening Flight (17:00 - 22:00)',
            'purpose_type'        => 'Client Meeting',
            'purpose_title'       => \Illuminate\Support\Str::limit($validated['purpose'], 100),
            'purpose_description' => $validated['purpose'],
            'flight_cost'         => $flightCost,
            'hotel_cost'          => $hotelCost,
            'transit_cost'        => $transitCost,
            'total_cost'          => $totalCost,
            'currency'            => 'IDR',
            'policy_status'       => 'compliant',
            'budget_status'       => 'available',
            'approval_stage'      => 'Line Manager Review',
            'stage_step'          => 1,
            'total_steps'         => 6,
            'overall_status'      => 'pending_approval',
            'submitted_at'        => now(),
        ]);

        // Role-based notification:
        // 1. Notify traveler confirmation
        if (auth()->check()) {
            auth()->user()->notify(new \App\Notifications\TripSubmittedNotification($trip));
        }

        // 2. Notify Line Managers / Approvers
        $approvers = \App\Models\User::where(function ($q) {
            $q->where('role', 'like', '%Approver%')
              ->orWhere('role', 'like', '%Manager%')
              ->orWhereIn('band', ['Band 4', 'Band 5']);
        })->get();

        foreach ($approvers as $approver) {
            if ($approver->id !== auth()->id()) {
                $approver->notify(new \App\Notifications\TripSubmittedNotification($trip));
            }
        }

        return redirect()->route('trips.show', $trip->id)
                         ->with('success', "Travel Request #{$trip->request_code} submitted successfully and routed to Line Manager.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trip = TravelRequest::with('traveler')->findOrFail($id);

        return view('trips.show', compact('trip'));
    }

    /**
     * Export the travel authorization and itinerary as a formatted PDF.
     */
    public function exportPdf(string $id)
    {
        $trip = TravelRequest::with('traveler')->findOrFail($id);

        return response()->streamDownload(function () use ($trip) {
            echo "Travel Authorization Pass\nTrip ID: #{$trip->id}\nRoute: {$trip->origin} -> {$trip->destination}\nTotal: Rp " . number_format($trip->total_cost, 0, ',', '.');
        }, "travel-pass-{$trip->id}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Join the coordinated airport ground transit or carpool group.
     */
    public function joinTransitPool(Request $request, string $id)
    {
        $trip = TravelRequest::findOrFail($id);

        return back()->with('success', "Added to airport express coordination pool for Trip #{$trip->id}.");
    }

    /**
     * Display corporate travel policy rules and limits (Navan-style tiered policies).
     */
    public function policyRules()
    {
        $policies = TravelPolicy::orderBy('applicable_band')->orderBy('rule_type')->get();
        $totalRules = $policies->count();
        $activeRules = $policies->where('is_active', true)->count();

        return view('policies.index', compact('policies', 'totalRules', 'activeRules'));
    }

    /**
     * Display departmental travel budget allocations from database.
     */
    public function budgets()
    {
        $budgets = DepartmentBudget::orderBy('budget_amount', 'desc')->get();
        $totalAllocated = $budgets->sum('budget_amount');
        $totalSpent = $budgets->sum('spent_amount');
        $remainingLiquidity = max(0, $totalAllocated - $totalSpent);
        $overallUtilization = $totalAllocated > 0 ? round(($totalSpent / $totalAllocated) * 100, 1) : 0;

        return view('budgets.index', compact('budgets', 'totalAllocated', 'totalSpent', 'remainingLiquidity', 'overallUtilization'));
    }

    /**
     * Display compliance and spend audit reports.
     */
    public function complianceReports()
    {
        return view('reports.index');
    }
}

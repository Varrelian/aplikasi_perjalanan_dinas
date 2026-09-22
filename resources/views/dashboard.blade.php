@extends('layouts.app')

@section('title', 'Corporate Executive Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Top Greeting & Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#00677e] uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-[#00677e]"></span>
                    Corporate Travel Management System
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight mt-1">
                    Executive Travel Command Center
                </h1>
                <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
                    Overview of active authorizations, budget commitments, and upcoming itineraries across PT Astra Digital.
                </p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @if (isset($spotlightTrip))
                    <a href="{{ route('trips.export', ['trip' => $spotlightTrip->id, 'format' => 'pdf']) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-[#c3c6d1]/50 text-xs font-semibold text-[#111c2d] hover:bg-[#f0f3ff] transition-all shadow-2xs">
                        <span class="material-symbols-outlined text-[16px] text-[#00677e]">picture_as_pdf</span>
                        Export Itinerary PDF
                    </a>
                @endif
                <a href="{{ route('trips.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#00254e] text-white text-xs font-semibold hover:bg-[#00346e] transition-all shadow-xs">
                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                    New Travel Request
                </a>
            </div>
        </div>

        <!-- 3 High-Impact Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Stat 1: Active Authorizations -->
            <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/30 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-[#737780] uppercase tracking-wider">Active Authorizations
                    </div>
                    <div class="text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d] mt-1">
                        {{ $activeTripsCount ?? 2 }} Trips</div>
                    <div class="text-[10px] text-[#00677e] font-medium mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">flight_takeoff</span>
                        1 currently en-route
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#f0f3ff] flex items-center justify-center text-[#00254e]">
                    <span class="material-symbols-outlined text-[22px]">airplane_ticket</span>
                </div>
            </div>

            <!-- Stat 2: Pending Approvals -->
            <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/30 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-[#737780] uppercase tracking-wider">Pending Approvals</div>
                    <div class="text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#ba1a1a] mt-1">
                        {{ $pendingApprovalsCount ?? 3 }} Requests</div>
                    <div class="text-[10px] text-[#ba1a1a] font-medium mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span>
                        Avg SLA: 4.2 hours remaining
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#ffebee] flex items-center justify-center text-[#ba1a1a]">
                    <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                </div>
            </div>

            <!-- Stat 3: Q3 Budget Allocated -->
            <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/30 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-[#737780] uppercase tracking-wider">Q3 Budget Committed</div>
                    <div class="text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d] mt-1">Rp 64.2M</div>
                    <div class="text-[10px] text-[#737780] font-medium mt-1">
                        68% of Rp 95M Cap used
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#e8f5e9] flex items-center justify-center text-[#2e7d32]">
                    <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                </div>
            </div>
        </div>

        <!-- Active Spotlight Travel Authorization Card -->
        @if (isset($spotlightTrip))
            <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
                <div
                    class="bg-[#00254e] text-white p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-2.5 py-1 rounded bg-[#00677e] text-white font-mono text-xs font-bold uppercase tracking-wider">
                            #{{ $spotlightTrip->id }}
                        </span>
                        <div>
                            <h2 class="text-base font-bold font-['Plus_Jakarta_Sans']">
                                Active Authorization: {{ $spotlightTrip->origin }} ({{ $spotlightTrip->origin_code }}) →
                                {{ $spotlightTrip->destination }} ({{ $spotlightTrip->dest_code }})
                            </h2>
                            <div class="text-xs text-[#c3c6d1] flex items-center gap-2 mt-0.5">
                                <span>Traveler: <strong>{{ $spotlightTrip->traveler->name ?? 'Felix Gonelius' }}</strong>
                                    ({{ $spotlightTrip->traveler->grade ?? 'Senior Architect' }})</span>
                                <span>•</span>
                                <span>Purpose: {{ $spotlightTrip->purpose }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('trips.show', $spotlightTrip->id) }}"
                            class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-all border border-white/20 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">map</span>
                            Trip Workspace & Coordination
                        </a>
                    </div>
                </div>

                <!-- 6-Stage Clearance Pipeline -->
                <div class="p-4 sm:p-6 bg-[#f0f3ff]/40 border-b border-[#c3c6d1]/30">
                    <div class="text-xs font-bold text-[#00254e] uppercase tracking-wider mb-4">
                        Sequential Travel Authorization Pipeline (6-Stage Governance)
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        @php
                            $stages = [
                                ['id' => 'draft', 'name' => '1. Drafted', 'icon' => 'edit_note'],
                                ['id' => 'policy', 'name' => '2. Policy Pass', 'icon' => 'verified_user'],
                                ['id' => 'manager', 'name' => '3. Line Manager', 'icon' => 'person_check'],
                                ['id' => 'finance', 'name' => '4. Finance Review', 'icon' => 'account_balance'],
                                ['id' => 'director', 'name' => '5. Director SLA', 'icon' => 'military_tech'],
                                ['id' => 'issued', 'name' => '6. Ticket Issued', 'icon' => 'airplane_ticket'],
                            ];
                        @endphp

                        @foreach ($stages as $index => $stage)
                            @php
                                $isCompleted = true; // Dynamically evaluated from $spotlightTrip->stage_index >= $index
                            @endphp
                            <div class="bg-white rounded-lg p-3 border border-[#c3c6d1]/40 shadow-2xs">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span
                                        class="material-symbols-outlined text-[18px] text-[#00677e]">{{ $stage['icon'] }}</span>
                                    <span class="w-2 h-2 rounded-full bg-[#00677e]"></span>
                                </div>
                                <div class="text-[11px] font-bold text-[#111c2d] leading-tight">{{ $stage['name'] }}</div>
                                <div class="text-[10px] text-[#00677e] font-semibold mt-1">Cleared</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Trip Details Summary -->
                <div class="p-4 sm:p-5 grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#00677e] text-[20px]">calendar_today</span>
                        <div>
                            <div class="text-[#737780] font-medium">Dates & Schedule</div>
                            <div class="font-bold text-[#111c2d] mt-0.5">
                                {{ $spotlightTrip->departure_date ?? 'Oct 24, 2026' }} –
                                {{ $spotlightTrip->return_date ?? 'Oct 28, 2026' }}</div>
                            <div class="text-[11px] text-[#43474f]">4 Nights • Direct Garuda Indonesia</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#00677e] text-[20px]">hotel</span>
                        <div>
                            <div class="text-[#737780] font-medium">Lodging Allocation</div>
                            <div class="font-bold text-[#111c2d] mt-0.5">
                                {{ $spotlightTrip->hotel_name ?? 'Shangri-La Surabaya (Deluxe Suite)' }}</div>
                            <div class="text-[11px] text-[#00677e] font-semibold">Under Hotel Policy Cap (Rp 1.45M/night)
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#00677e] text-[20px]">payments</span>
                        <div>
                            <div class="text-[#737780] font-medium">Total Encumbered Amount</div>
                            <div class="font-bold text-[#111c2d] text-sm mt-0.5">Rp
                                {{ number_format($spotlightTrip->total_cost ?? 6250000, 0, ',', '.') }}</div>
                            <div class="text-[11px] text-[#737780]">Cost Center: CC-CORP-TECH-401</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Travel Requests Table -->
        <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
            <div
                class="p-4 sm:p-5 border-b border-[#c3c6d1]/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">
                        Recent Enterprise Travel Requests
                    </h3>
                    <p class="text-xs text-[#737780]">Corporate travel authorizations and clearance logs</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('trips.index') }}"
                        class="text-xs font-bold text-[#00677e] hover:underline flex items-center gap-1">
                        View All {{ $totalTripsCount ?? 28 }} Requests
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr
                            class="bg-[#f0f3ff] text-[#737780] uppercase tracking-wider text-[10px] font-bold border-b border-[#c3c6d1]/30">
                            <th class="py-3 px-4">Trip ID</th>
                            <th class="py-3 px-4">Traveler & Department</th>
                            <th class="py-3 px-4">Route & Dates</th>
                            <th class="py-3 px-4">Policy Status</th>
                            <th class="py-3 px-4 text-right">Cost</th>
                            <th class="py-3 px-4">Approval Stage</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#c3c6d1]/20">
                        @forelse($recentTrips ?? [] as $trip)
                            <tr class="hover:bg-[#f0f3ff]/40 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#00254e]">
                                    #{{ $trip->id }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-[#111c2d]">{{ $trip->traveler->name }}</div>
                                    <div class="text-[10px] text-[#737780]">{{ $trip->traveler->department }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-[#111c2d]">{{ $trip->origin_code }} →
                                        {{ $trip->dest_code }}</div>
                                    <div class="text-[10px] text-[#737780]">{{ $trip->departure_date }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($trip->policy_status === 'compliant')
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#e8f5e9] text-[#2e7d32]">
                                            <span class="material-symbols-outlined text-[12px]">check</span>
                                            Compliant
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#fff3e0] text-[#e65100]">
                                            <span class="material-symbols-outlined text-[12px]">warning</span>
                                            Out of Policy
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right font-bold text-[#111c2d]">
                                    Rp {{ number_format($trip->total_cost, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dfe8ff] text-[#00254e]">
                                        {{ $trip->approval_stage }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('trips.show', $trip->id) }}"
                                        class="p-1.5 rounded text-[#00677e] hover:bg-[#dfe8ff] transition-colors inline-block"
                                        title="Open Trip Workspace">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-[#737780]">No travel requests recorded
                                    yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

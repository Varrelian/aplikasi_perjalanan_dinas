@extends('layouts.app')

@section('title', 'Corporate Travel Requests (Trips Index)')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-[#737780] mb-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-[#00254e] transition-colors">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#00254e] font-semibold">Trip Workspace</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight">
                Trip Workspace
            </h1>
            <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
                Centralized registry of corporate travel authorisations, multi-stage approval tracking, and booking clearance.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trips.create') }}" class="h-10 px-4 bg-[#00254e] hover:bg-[#0b3b70] text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Create Travel Request</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Total Requisitions</span>
                <span class="material-symbols-outlined text-[18px] text-[#00677e]">flight_takeoff</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $trips->total() ?? 24 }}
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                <span>Active Q4 Itineraries</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Pending Approval</span>
                <span class="material-symbols-outlined text-[18px] text-amber-500">pending_actions</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $trips->where('overall_status', 'pending_approval')->count() ?: 3 }}
            </div>
            <div class="text-[11px] text-amber-600 font-semibold mt-1">
                Awaiting Manager / Finance SLA
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Booking Ready</span>
                <span class="material-symbols-outlined text-[18px] text-emerald-500">verified</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $trips->where('overall_status', 'booking_ready')->count() ?: 8 }}
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                Authorized for Ticketing
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Completed Trips</span>
                <span class="material-symbols-outlined text-[18px] text-[#00254e]">task_alt</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $trips->where('overall_status', 'completed')->count() ?: 12 }}
            </div>
            <div class="text-[11px] text-[#737780] font-semibold mt-1">
                Archived &amp; Reconciled
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('trips.index') }}" class="flex-1 flex flex-wrap items-center gap-3">
            <!-- Search Query Input -->
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-[#737780]">
                    search
                </span>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by trip code, destination, or traveler name..."
                    class="w-full h-10 pl-9 pr-4 text-xs font-medium bg-[#f0f3ff]/60 border border-[#c3c6d1]/60 rounded-xl focus:border-[#00677e] focus:bg-white focus:outline-none transition-all"
                >
            </div>

            <!-- Status Filter -->
            <div class="min-w-[150px]">
                <select name="status" onchange="this.form.submit()" class="w-full h-10 px-3 text-xs font-medium bg-white border border-[#c3c6d1]/70 rounded-xl focus:border-[#00677e] focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="booking_ready" {{ request('status') == 'booking_ready' ? 'selected' : '' }}>Booking Ready</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked / Ticketed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <button type="submit" class="h-10 px-4 bg-[#00254e] hover:bg-[#001833] text-white text-xs font-bold rounded-xl transition-colors cursor-pointer">
                Filter
            </button>
            @if(request('q') || request('status'))
                <a href="{{ route('trips.index') }}" class="h-10 px-3 border border-[#c3c6d1]/70 hover:bg-[#f0f3ff] text-xs font-semibold text-[#43474f] rounded-xl flex items-center gap-1 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2 self-end md:self-auto text-xs text-[#737780]">
            <span>Showing {{ $trips->count() ?? 10 }} records</span>
        </div>
    </div>

    <!-- Trips Data Table -->
    <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f0f3ff]/60 border-b border-[#c3c6d1]/40 text-[11px] font-bold uppercase tracking-wider text-[#737780]">
                        <th class="py-3.5 px-4">Trip Code</th>
                        <th class="py-3.5 px-4">Traveler</th>
                        <th class="py-3.5 px-4">Itinerary</th>
                        <th class="py-3.5 px-4">Dates</th>
                        <th class="py-3.5 px-4">Cost (IDR)</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Stage / SLA</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#c3c6d1]/20 text-xs">
                    @forelse($trips as $trip)
                    <tr class="hover:bg-[#f8f9ff] transition-colors">
                        <!-- Trip Code & Purpose -->
                        <td class="py-3.5 px-4 font-mono font-bold text-[#00677e]">
                            <a href="{{ route('trips.show', $trip->id) }}" class="hover:underline flex flex-col">
                                <span>{{ $trip->request_code ?? $trip->id }}</span>
                                <span class="text-[10px] font-sans font-normal text-[#737780] line-clamp-1">
                                    {{ $trip->purpose_title ?? $trip->purpose }}
                                </span>
                            </a>
                        </td>

                        <!-- Traveler Profile -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                @if(optional($trip->traveler)->avatar_url)
                                    <img src="{{ $trip->traveler->avatar_url }}" alt="{{ $trip->traveler->name }}" class="w-7 h-7 rounded-full object-cover border border-[#c3c6d1]/50">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-[#00254e] text-white flex items-center justify-center text-[10px] font-bold">
                                        {{ substr(optional($trip->traveler)->name ?? 'TR', 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-[#111c2d]">
                                        {{ optional($trip->traveler)->name ?? 'Corporate Traveler' }}
                                    </div>
                                    <div class="text-[10px] text-[#737780]">
                                        {{ optional($trip->traveler)->department ?? 'Operations' }} &bull; {{ optional($trip->traveler)->band ?? 'Band 2' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Itinerary Origin -> Destination -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-1.5 font-semibold text-[#111c2d]">
                                <span class="px-1.5 py-0.5 rounded bg-gray-100 text-[10px] font-mono font-bold">
                                    {{ $trip->origin_code ?? 'CGK' }}
                                </span>
                                <span class="material-symbols-outlined text-[14px] text-[#737780]">arrow_forward</span>
                                <span class="px-1.5 py-0.5 rounded bg-[#00677e]/10 text-[#00677e] text-[10px] font-mono font-bold">
                                    {{ $trip->dest_code ?? 'SUB' }}
                                </span>
                            </div>
                            <div class="text-[10px] text-[#737780] mt-0.5">
                                {{ $trip->origin ?? 'Jakarta' }} to {{ $trip->destination ?? 'Surabaya' }}
                            </div>
                        </td>

                        <!-- Travel Dates -->
                        <td class="py-3.5 px-4 text-[#43474f]">
                            <div class="font-medium">
                                {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}
                            </div>
                            <div class="text-[10px] text-[#737780]">
                                return {{ \Carbon\Carbon::parse($trip->return_date)->format('d M Y') }}
                            </div>
                        </td>

                        <!-- Estimated Cost -->
                        <td class="py-3.5 px-4 font-bold text-[#111c2d]">
                            Rp {{ number_format($trip->total_cost ?? 3200000, 0, ',', '.') }}
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4">
                            @php
                                $status = $trip->overall_status ?? 'booking_ready';
                            @endphp
                            @if($status === 'booking_ready' || $status === 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200/60 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Ready
                                </span>
                            @elseif($status === 'pending_approval' || $status === 'pending_review')
                                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-200/60 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-[#f0f3ff] text-[#00254e] text-[11px] font-bold border border-[#c3c6d1]/40 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00254e]"></span>
                                    En Route
                                </span>
                            @endif
                        </td>

                        <!-- Approval Stage / Progress -->
                        <td class="py-3.5 px-4">
                            <div class="text-xs font-semibold text-[#00254e]">
                                {{ $trip->approval_stage ?? 'Line Manager Review' }}
                            </div>
                            <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1 overflow-hidden">
                                <div class="bg-[#00677e] h-1.5 rounded-full" style="width: {{ (($trip->stage_step ?? 3) / ($trip->total_steps ?? 6)) * 100 }}%"></div>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('trips.show', $trip->id) }}" class="p-1.5 text-[#00677e] hover:bg-[#00677e]/10 rounded-lg transition-colors cursor-pointer" title="Open Workspace">
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                </a>
                                <a href="{{ route('trips.export', $trip->id) }}" class="p-1.5 text-gray-500 hover:text-[#00254e] hover:bg-gray-100 rounded-lg transition-colors cursor-pointer" title="Export PDF">
                                    <span class="material-symbols-outlined text-[18px]">download</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <span class="material-symbols-outlined text-[40px] text-gray-300 block mb-2">flight_takeoff</span>
                            <p class="text-sm font-semibold text-[#111c2d]">No travel requisitions found</p>
                            <p class="text-xs text-[#737780] mt-1">Get started by submitting your first corporate trip requisition.</p>
                            <a href="{{ route('trips.create') }}" class="inline-flex items-center gap-1.5 mt-3 px-3.5 py-1.5 bg-[#00677e] text-white text-xs font-bold rounded-xl shadow-xs hover:bg-[#005568] transition-colors">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                Create Request
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if(method_exists($trips, 'links'))
        <div class="px-4 py-3 bg-[#f0f3ff]/40 border-t border-[#c3c6d1]/30 flex items-center justify-between text-xs text-[#737780]">
            <div>
                Showing <strong>{{ $trips->firstItem() ?? 1 }}</strong> to <strong>{{ $trips->lastItem() ?? $trips->count() }}</strong> of <strong>{{ $trips->total() ?? $trips->count() }}</strong> trips
            </div>
            <div>
                {{ $trips->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

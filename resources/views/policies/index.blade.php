@extends('layouts.app')

@section('title', 'Corporate Travel Policies & Navan-Style Compliance Engine')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb & Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-[#737780] mb-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-[#00254e] transition-colors">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#00254e] font-semibold">Travel Policies</span>
            </nav>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight">
                    Corporate Travel Policies
                </h1>
                <span class="px-2.5 py-0.5 rounded-full bg-[#00677e]/10 text-[#00677e] text-xs font-bold border border-[#00677e]/20 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">verified</span>
                    Navan Dynamic Policy Engine
                </span>
            </div>
            <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
                Automated fare caps, cabin class entitlements, dynamic hotel limits, and proactive employee savings rewards.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trips.create') }}" class="h-10 px-4 bg-[#00254e] hover:bg-[#001833] text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 transition-all">
                <span class="material-symbols-outlined text-[18px]">rule</span>
                <span>Test Policy Simulator</span>
            </a>
        </div>
    </div>

    <!-- Navan Engine Feature Highlight Banner -->
    <div class="p-4 rounded-xl bg-linear-to-r from-[#00254e] to-[#004e60] text-white shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                <span class="material-symbols-outlined text-[24px] text-amber-300">loyalty</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold font-['Plus_Jakarta_Sans']">Navan-Style Cost-Saving Employee Rewards Active</h3>
                    <span class="bg-emerald-500/20 text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-400/30">
                        Employees Earn 10% Of Savings
                    </span>
                </div>
                <p class="text-xs text-white/80 mt-0.5 max-w-2xl leading-relaxed">
                    Employees who book flights or hotel nights below the corporate dynamic price cap receive rewards points redeemable for gift cards, personal vacation upgrades, or charity donations.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="text-right">
                <div class="text-xs text-white/70">Q3 Employee Savings</div>
                <div class="text-base font-bold text-amber-300">Rp 48.500.000</div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Active Rules</span>
                <span class="material-symbols-outlined text-[18px] text-[#00677e]">verified_user</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $activeRules ?? 5 }} <span class="text-xs font-normal text-gray-500">/ {{ $totalRules ?? 5 }}</span>
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>100% Policy Health</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Employee Bands Covered</span>
                <span class="material-symbols-outlined text-[18px] text-[#00254e]">badge</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                5 Tiers
            </div>
            <div class="text-[11px] text-[#737780] font-semibold mt-1">
                Band 1 (Associates) to Band 5 (C-Suite)
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Advance Booking Window</span>
                <span class="material-symbols-outlined text-[18px] text-amber-500">calendar_month</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                14 Days
            </div>
            <div class="text-[11px] text-amber-600 font-semibold mt-1">
                Mandatory for Best Fare Rates
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Out-of-Policy Auto Block</span>
                <span class="material-symbols-outlined text-[18px] text-red-500">block</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                Enabled
            </div>
            <div class="text-[11px] text-red-600 font-semibold mt-1">
                Requires VP / CFO Override
            </div>
        </div>
    </div>

    <!-- Policy Rules Catalog by Category -->
    <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-[#c3c6d1]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                    Configured Policy Rules Matrix
                </h2>
                <p class="text-xs text-[#737780] mt-0.5">
                    Governs itinerary compliance checks during trip reservation and approval routing.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Real-time Enforcement
                </span>
            </div>
        </div>

        <div class="divide-y divide-[#c3c6d1]/20">
            <!-- Rule 1: Flights -->
            <div class="p-5 hover:bg-[#f8f9ff] transition-colors">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#00677e]/10 text-[#00677e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">flight</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-[#111c2d]">Domestic Flight Fare Ceiling</h3>
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200">
                                    Flight Limit
                                </span>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">
                                    Band 1 &bull; Domestic
                                </span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </div>
                            <p class="text-xs text-[#43474f] mt-1 leading-relaxed">
                                Direct economy flights must not exceed Rp 2.000.000 per leg for domestic itineraries under 3 hours. Flights booked within 14 days of departure require documented manager justification.
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-[#737780]">
                                <span><strong>Price Cap:</strong> Rp 2.000.000 / leg</span>
                                <span><strong>Class:</strong> Standard Economy</span>
                                <span><strong>Enforcement:</strong> Soft Exception (Manager Approval)</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">Rp 2.000.000</span>
                        <div class="text-[10px] text-[#737780]">Max / flight leg</div>
                    </div>
                </div>
            </div>

            <!-- Rule 2: Hotels Tier 1 -->
            <div class="p-5 hover:bg-[#f8f9ff] transition-colors">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">hotel</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-[#111c2d]">Hotel Nightly Rate Cap - Tier 1 Hubs</h3>
                                <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                    Hotel Limit
                                </span>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">
                                    Band 1 &bull; All Hubs
                                </span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </div>
                            <p class="text-xs text-[#43474f] mt-1 leading-relaxed">
                                Standard accommodation cap of Rp 500.000 per night inclusive of tax and breakfast in major metropolitan hubs (Jakarta, Surabaya, Bandung, Medan).
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-[#737780]">
                                <span><strong>Nightly Cap:</strong> Rp 500.000 / night</span>
                                <span><strong>Rating:</strong> Up to 3.5 Stars</span>
                                <span><strong>Amenity:</strong> Breakfast included mandatory</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">Rp 500.000</span>
                        <div class="text-[10px] text-[#737780]">Per room / night</div>
                    </div>
                </div>
            </div>

            <!-- Rule 3: Senior Consultant Hotel Cap -->
            <div class="p-5 hover:bg-[#f8f9ff] transition-colors">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">apartment</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-[#111c2d]">Senior Consultant &amp; Architect Accommodation Tier</h3>
                                <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-200">
                                    Hotel Limit
                                </span>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">
                                    Band 2 &bull; All Hubs
                                </span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </div>
                            <p class="text-xs text-[#43474f] mt-1 leading-relaxed">
                                Senior Consultants, Technical Leads, and Client Partners authorized up to Rp 750.000/night for 4-star corporate partner accommodations situated within 5km of client facility.
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-[#737780]">
                                <span><strong>Nightly Cap:</strong> Rp 750.000 / night</span>
                                <span><strong>Rating:</strong> 4 Stars Authorized</span>
                                <span><strong>Proximity:</strong> &lt; 5km to Client Office</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">Rp 750.000</span>
                        <div class="text-[10px] text-[#737780]">Per room / night</div>
                    </div>
                </div>
            </div>

            <!-- Rule 4: Executive Business Flight Privilege -->
            <div class="p-5 hover:bg-[#f8f9ff] transition-colors">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#00254e]/10 text-[#00254e] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">flight_class</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-[#111c2d]">Executive Business Flight Privilege</h3>
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200">
                                    Cabin Class
                                </span>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">
                                    Band 4 &amp; 5 &bull; International
                                </span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </div>
                            <p class="text-xs text-[#43474f] mt-1 leading-relaxed">
                                Directors, Vice Presidents, and C-Suite traveling on long-haul routes exceeding 5 hours flight duration are authorized for Business Class. First Class strictly prohibited without Board consent.
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-[#737780]">
                                <span><strong>Allowed Class:</strong> Business Class</span>
                                <span><strong>Duration Threshold:</strong> &gt; 5 Hours</span>
                                <span><strong>First Class:</strong> Strictly Prohibited</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">Business Class</span>
                        <div class="text-[10px] text-[#737780]">Long-haul routes</div>
                    </div>
                </div>
            </div>

            <!-- Rule 5: Daily Transit & Per-Diem -->
            <div class="p-5 hover:bg-[#f8f9ff] transition-colors">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">payments</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-[#111c2d]">Daily Transit &amp; Per-Diem Allowance</h3>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                    Per Diem
                                </span>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">
                                    All Bands &bull; All Scopes
                                </span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                                </span>
                            </div>
                            <p class="text-xs text-[#43474f] mt-1 leading-relaxed">
                                Daily ground transportation, regional rideshare (Grab/Gojek/Bluebird), and meal allowance per full working travel day. No itemized receipts required below threshold.
                            </p>
                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-[#737780]">
                                <span><strong>Daily Cap:</strong> Rp 350.000 / day</span>
                                <span><strong>Ground Transit:</strong> Airport Express or Rideshare</span>
                                <span><strong>Receipts:</strong> Audit-free below cap</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">Rp 350.000</span>
                        <div class="text-[10px] text-[#737780]">Per day / traveler</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Policy Guidelines -->
        <div class="p-4 bg-[#f0f3ff]/50 border-t border-[#c3c6d1]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-[#737780]">
            <div>
                Policies synchronized with <strong>TravelSys Human Resources &amp; Corporate Finance Handbook FY2026</strong>.
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-[#00677e]">sync</span>
                <span>Auto-validated on every requisition submission</span>
            </div>
        </div>
    </div>
</div>
@endsection

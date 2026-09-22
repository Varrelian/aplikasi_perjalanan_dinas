@extends('layouts.app')

@section('title', 'New Travel Request & Policy Pre-Check')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex items-center gap-1.5 text-xs text-[#737780] mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-[#00254e]">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('trips.index') }}" class="hover:text-[#00254e]">Trips</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-[#00254e] font-semibold">Travel Requests</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight">
            Travel Requests
        </h1>
        <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
            Submit business trip requirements with automated expense estimation and department budget allocation.
        </p>
    </div>

    <!-- Main Request Form -->
    <form action="{{ route('trips.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Step 1: Trip Scope & Traveler Info -->
        <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs p-5 sm:p-6 space-y-4">
            <div class="flex items-center gap-2 border-b border-[#c3c6d1]/20 pb-3">
                <span class="w-6 h-6 rounded-full bg-[#00254e] text-white flex items-center justify-center text-xs font-bold">1</span>
                <h2 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                    Trip Scope & Itinerary Details
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Traveler Employee</label>
                    <input type="text" value="{{ auth()->user()->name ?? 'Felix Gonelius' }} ({{ auth()->user()->grade ?? 'Senior Architect' }})" disabled class="w-full h-10 px-3 bg-[#f0f3ff] border border-[#c3c6d1]/50 rounded-lg text-xs font-medium text-[#43474f] cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Cost Center & Department</label>
                    <select name="cost_center_id" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                        <option value="CC-CORP-TECH-401">CC-CORP-TECH-401 • Technology Architecture (Rp 30.8M Remaining)</option>
                        <option value="CC-CORP-SLS-202">CC-CORP-SLS-202 • Commercial & Enterprise Sales (Rp 45.2M Remaining)</option>
                        <option value="CC-CORP-ENG-105">CC-CORP-ENG-105 • Site Engineering & Field Ops (Rp 18.0M Remaining)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Departure City / Airport</label>
                    <select name="origin_code" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                        <option value="CGK" selected>Jakarta - Soekarno-Hatta (CGK)</option>
                        <option value="HLP">Jakarta - Halim Perdanakusuma (HLP)</option>
                        <option value="SUB">Surabaya - Juanda (SUB)</option>
                        <option value="DPS">Bali - Ngurah Rai (DPS)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Destination City / Airport</label>
                    <select name="dest_code" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                        <option value="SUB" selected>Surabaya - Juanda (SUB)</option>
                        <option value="DPS">Bali - Ngurah Rai (DPS)</option>
                        <option value="SIN">Singapore - Changi (SIN)</option>
                        <option value="KNO">Medan - Kualanamu (KNO)</option>
                        <option value="BPN">Balikpapan - Sepinggan (BPN)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Departure Date</label>
                    <input type="date" name="departure_date" value="2026-10-24" required class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Return Date</label>
                    <input type="date" name="return_date" value="2026-10-28" required class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Business Justification & Objectives</label>
                    <textarea name="purpose" rows="2" required placeholder="Describe executive meetings, client project milestones, or field audits..." class="w-full p-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">Astra Infra Surabaya ERP Migration & Executive On-site Architecture Sign-off.</textarea>
                </div>
            </div>
        </div>

        <!-- Step 2: Policy Simulation & Budget Calculation -->
        <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs p-5 sm:p-6 space-y-4">
            <div class="flex items-center gap-2 border-b border-[#c3c6d1]/20 pb-3">
                <span class="w-6 h-6 rounded-full bg-[#00254e] text-white flex items-center justify-center text-xs font-bold">2</span>
                <h2 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                    Estimated Costs &amp; Budget Calculation
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Estimated Airfare (IDR)</label>
                    <input type="number" name="flight_cost" value="2400000" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                    <span class="text-[10px] text-[#737780] mt-1 block">Economy class policy limit: Rp 3.500.000</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Estimated Hotel (IDR)</label>
                    <input type="number" name="hotel_cost" value="3850000" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                    <span class="text-[10px] text-[#737780] mt-1 block">4 nights @ Rp 962.500 (Max: Rp 1.5M/night)</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Per Diem & Ground Meals (IDR)</label>
                    <input type="number" name="per_diem_cost" value="800000" class="w-full h-10 px-3 bg-white border border-[#c3c6d1]/70 rounded-lg text-xs font-medium text-[#111c2d] focus:border-[#00677e] focus:outline-none">
                    <span class="text-[10px] text-[#737780] mt-1 block">Standard allowance: Rp 200.000 / day</span>
                </div>
            </div>

            <!-- Real-time Compliance Status Banner -->
            <div class="p-4 rounded-xl bg-[#e8f5e9] border border-[#a5d6a7] flex items-start gap-3">
                <span class="material-symbols-outlined text-[#2e7d32] text-[22px] mt-0.5">verified_user</span>
                <div>
                    <div class="text-xs font-bold text-[#1b5e20]">Policy Status: 100% Compliant with Astra Travel Guidelines</div>
                    <div class="text-[11px] text-[#2e7d32] mt-0.5">
                        Selected flights and hotel allocations satisfy Grade L4 allowances. Total estimated cost is <strong>Rp 7.050.000</strong>, falling well within the domestic trip ceiling.
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Out-of-Policy Exceptions (Conditional) -->
        <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs p-5 sm:p-6 space-y-4">
            <div class="flex items-center gap-2 border-b border-[#c3c6d1]/20 pb-3">
                <span class="w-6 h-6 rounded-full bg-[#00254e] text-white flex items-center justify-center text-xs font-bold">3</span>
                <h2 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                    Supporting Documents & Approver Hierarchy
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Meeting Invite / Client SOW (PDF)</label>
                    <input type="file" name="attachment" accept=".pdf,.doc,.docx" class="w-full text-xs text-[#737780] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#f0f3ff] file:text-[#00254e] hover:file:bg-[#dfe8ff]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#111c2d] mb-1">Approval Tier Requirement</label>
                    <div class="p-3 bg-[#f0f3ff] rounded-lg text-xs text-[#00254e] space-y-1">
                        <div class="font-bold">Tier 1: Line Manager (Bambang Soediro)</div>
                        <div class="text-[#737780]">Tier 2: Finance Controller (Auto-routed if > Rp 10M)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-lg border border-[#c3c6d1]/60 text-xs font-semibold text-[#43474f] hover:bg-[#f0f3ff] transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#00254e] text-white text-xs font-bold hover:bg-[#00346e] transition-all shadow-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">send</span>
                Submit Travel Authorization
            </button>
        </div>
    </form>
</div>
@endsection

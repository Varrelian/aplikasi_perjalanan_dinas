@extends('layouts.app')

@section('title', 'Trip #' . $trip->id . ' — Operational Workspace')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-[#737780] mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#00254e]">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('trips.index') }}" class="hover:text-[#00254e]">Trips</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#00254e] font-semibold">#{{ $trip->id }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight">
                    Trip #{{ $trip->id }}: {{ $trip->origin }} → {{ $trip->destination }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#dfe8ff] text-[#00254e]">
                    {{ $trip->approval_stage ?? 'Ticket Issued / Confirmed' }}
                </span>
            </div>
            <p class="text-xs text-[#43474f] mt-1">
                Traveler: <strong>{{ $trip->traveler->name ?? 'Felix Gonelius' }}</strong> ({{ $trip->traveler->department ?? 'Technology' }}) • Purpose: {{ $trip->purpose ?? 'Astra Infra Surabaya Migration' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('trips.export', ['trip' => $trip->id, 'format' => 'pdf']) ?? '#' }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-[#c3c6d1]/50 text-xs font-semibold text-[#111c2d] hover:bg-[#f0f3ff] transition-all shadow-2xs">
                <span class="material-symbols-outlined text-[16px] text-[#00677e]">download</span>
                Download Travel Pass (PDF)
            </a>
            <button type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#00254e] text-white text-xs font-semibold hover:bg-[#00346e] transition-all shadow-xs">
                <span class="material-symbols-outlined text-[16px]">share</span>
                Share Itinerary
            </button>
        </div>
    </div>

    <!-- Main Workspace Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left 8 Cols: Flight & Hotel Allocations -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Flight Schedule Card -->
            <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
                <div class="p-4 bg-[#f0f3ff]/60 border-b border-[#c3c6d1]/30 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#00677e] text-[20px]">flight</span>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-[#00254e]">
                            Commercial Airline Allocation (Confirmed)
                        </h2>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#00677e]">PNR: GA-84291</span>
                </div>

                <div class="p-5 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#c3c6d1]/20">
                        <div>
                            <div class="text-xs font-bold text-[#737780]">OUTBOUND FLIGHT</div>
                            <div class="text-base font-bold text-[#111c2d] mt-0.5">Garuda Indonesia GA-312</div>
                            <div class="text-xs text-[#43474f]">Boeing 737-800 • Seat 14A (Standard Economy)</div>
                        </div>

                        <div class="flex items-center gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">08:15</div>
                                <div class="text-[10px] text-[#737780]">CGK (Terminal 3)</div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-[10px] text-[#737780]">1h 35m</span>
                                <div class="w-16 h-0.5 bg-[#00677e] relative">
                                    <span class="material-symbols-outlined absolute -top-2 left-1/2 -translate-x-1/2 text-[14px] text-[#00677e]">flight</span>
                                </div>
                                <span class="text-[9px] text-[#2e7d32] font-semibold">Non-stop</span>
                            </div>
                            <div>
                                <div class="text-lg font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">09:50</div>
                                <div class="text-[10px] text-[#737780]">SUB (Terminal 1)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Flight -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-xs font-bold text-[#737780]">INBOUND FLIGHT</div>
                            <div class="text-base font-bold text-[#111c2d] mt-0.5">Garuda Indonesia GA-325</div>
                            <div class="text-xs text-[#43474f]">Boeing 737-800 • Seat 12C (Standard Economy)</div>
                        </div>

                        <div class="flex items-center gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">17:30</div>
                                <div class="text-[10px] text-[#737780]">SUB (Terminal 1)</div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-[10px] text-[#737780]">1h 40m</span>
                                <div class="w-16 h-0.5 bg-[#00677e] relative">
                                    <span class="material-symbols-outlined absolute -top-2 left-1/2 -translate-x-1/2 text-[14px] text-[#00677e]">flight</span>
                                </div>
                                <span class="text-[9px] text-[#2e7d32] font-semibold">Non-stop</span>
                            </div>
                            <div>
                                <div class="text-lg font-bold font-['Plus_Jakarta_Sans'] text-[#00254e]">19:10</div>
                                <div class="text-[10px] text-[#737780]">CGK (Terminal 3)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotel & Accommodation Card -->
            <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
                <div class="p-4 bg-[#f0f3ff]/60 border-b border-[#c3c6d1]/30 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#00677e] text-[20px]">hotel</span>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-[#00254e]">
                            Hotel & Lodging Confirmation
                        </h2>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#2e7d32]">Confirmed & Direct Bill</span>
                </div>

                <div class="p-5 flex flex-col sm:flex-row items-start gap-4">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80" alt="Shangri-La Surabaya" class="w-full sm:w-36 h-28 object-cover rounded-lg border border-[#c3c6d1]/40">
                    <div class="flex-1 space-y-1">
                        <div class="text-base font-bold text-[#111c2d]">Shangri-La Hotel Surabaya</div>
                        <p class="text-xs text-[#737780]">Jl. Mayjen Sungkono No.120, Sawahan, Surabaya, Jawa Timur</p>
                        <div class="text-xs text-[#43474f] pt-1">
                            <strong>4 Nights:</strong> Oct 24, 2026 – Oct 28, 2026 • Executive King Room
                        </div>
                        <div class="inline-flex items-center gap-1 text-[11px] text-[#00677e] font-semibold bg-[#dfe8ff] px-2 py-0.5 rounded">
                            <span class="material-symbols-outlined text-[14px]">local_cafe</span>
                            Corporate Rate: Daily Breakfast + High-speed Wi-Fi included
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 4 Cols: Smart Colleague Pooling & Expense Tracking -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Smart Transit Pooling Engine Card -->
            <div class="bg-white rounded-xl border border-[#00677e]/30 shadow-xs overflow-hidden">
                <div class="p-4 bg-[#00254e] text-white">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-[#29b6f6]">group_work</span>
                        <h2 class="text-xs font-bold uppercase tracking-wider">Smart Colleague Pooling</h2>
                    </div>
                    <p class="text-[11px] text-[#c3c6d1] mt-1">
                        Colleagues arriving at SUB airport within 45 minutes of your flight.
                    </p>
                </div>

                <div class="p-4 space-y-3">
                    <div class="p-3 rounded-lg bg-[#f0f3ff] border border-[#c3c6d1]/40 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-[#111c2d]">Andi Pratama</div>
                            <div class="text-[10px] text-[#737780]">Batik Air ID-6590 • Arr: 10:05 AM</div>
                            <div class="text-[10px] text-[#00677e] font-semibold mt-0.5">Dest: Astra Infra Office</div>
                        </div>
                        <button type="button" class="px-2.5 py-1 rounded bg-[#00254e] text-white text-[10px] font-bold hover:bg-[#00346e]">
                            Join Pool
                        </button>
                    </div>

                    <div class="p-3 rounded-lg bg-[#f0f3ff] border border-[#c3c6d1]/40 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-[#111c2d]">Siti Rahmawati</div>
                            <div class="text-[10px] text-[#737780]">Garuda GA-312 • Same Flight!</div>
                            <div class="text-[10px] text-[#00677e] font-semibold mt-0.5">Dest: Shangri-La Hotel</div>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-[#e8f5e9] text-[#2e7d32] text-[10px] font-bold">
                            Paired
                        </span>
                    </div>

                    <div class="p-2.5 rounded-lg bg-[#e8f5e9] border border-[#81c784] text-[10px] text-[#1b5e20] font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">eco</span>
                        <span>Ground Transit Pooling saves <strong>Rp 450.000</strong> and 14kg CO₂.</span>
                    </div>
                </div>
            </div>

            <!-- Cost Encumbrance Breakdown -->
            <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs p-4 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#00254e]">
                    Cost Center Allocation
                </h3>

                <div class="space-y-2 text-xs divide-y divide-[#c3c6d1]/20">
                    <div class="flex justify-between py-1">
                        <span class="text-[#737780]">Roundtrip Airfare (Garuda)</span>
                        <span class="font-bold text-[#111c2d]">Rp 2.400.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-[#737780]">Hotel (4 Nights Shangri-La)</span>
                        <span class="font-bold text-[#111c2d]">Rp 3.850.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-[#737780]">Per Diem & Ground Transit</span>
                        <span class="font-bold text-[#111c2d]">Rp 800.000</span>
                    </div>
                    <div class="flex justify-between pt-2 text-sm font-bold text-[#00254e]">
                        <span>Total Encumbered</span>
                        <span>Rp 7.050.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

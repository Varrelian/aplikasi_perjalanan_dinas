@extends('layouts.app')

@section('title', 'Corporate Department Budgets & Cost Centers')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb & Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-[#737780] mb-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-[#00254e] transition-colors">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#00254e] font-semibold">Department Budgets</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight">
                Departmental Travel Budgets &amp; Cost Centers
            </h1>
            <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
                Fiscal Year 2026 corporate travel expenditure, remaining liquidity pool, and real-time cost center burn rates.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trips.create') }}" class="h-10 px-4 bg-[#00677e] hover:bg-[#005568] text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 transition-all">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Submit Requisition</span>
            </a>
        </div>
    </div>

    <!-- Organization Financial Summary KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Total Allocated (FY2026)</span>
                <span class="material-symbols-outlined text-[18px] text-[#00254e]">account_balance</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                Rp {{ number_format($totalAllocated ?? 1030000000, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-[#737780] font-semibold mt-1">
                5 Cost Centers Tracked
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Committed Expenditure</span>
                <span class="material-symbols-outlined text-[18px] text-amber-500">payments</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                Rp {{ number_format($totalSpent ?? 635000000, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-amber-600 font-semibold mt-1">
                Flights, Lodging &amp; Per-Diems
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Available Liquidity</span>
                <span class="material-symbols-outlined text-[18px] text-emerald-500">savings</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-emerald-700">
                Rp {{ number_format($remainingLiquidity ?? 395000000, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                Authorized for Q4 Travel
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-[#c3c6d1]/40 shadow-xs">
            <div class="flex items-center justify-between text-[#737780] text-xs font-medium mb-1">
                <span>Overall Utilization</span>
                <span class="material-symbols-outlined text-[18px] text-[#00677e]">pie_chart</span>
            </div>
            <div class="text-xl sm:text-2xl font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                {{ $overallUtilization ?? 61.7 }}%
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-[#00677e] h-1.5 rounded-full" style="width: {{ $overallUtilization ?? 61.7 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Cost Center Budgets Registry Table -->
    <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-[#c3c6d1]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold font-['Plus_Jakarta_Sans'] text-[#111c2d]">
                    Cost Center Allocation Registry (database.sql: department_budgets)
                </h2>
                <p class="text-xs text-[#737780] mt-0.5">
                    Live balances and expenditure utilization synchronized directly with database records.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-[#737780]">Fiscal Year: <strong>2026</strong></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#f0f3ff]/60 border-b border-[#c3c6d1]/40 text-[11px] font-bold uppercase tracking-wider text-[#737780]">
                        <th class="py-3.5 px-5">Cost Center</th>
                        <th class="py-3.5 px-4">Department</th>
                        <th class="py-3.5 px-4 text-right">Annual Budget</th>
                        <th class="py-3.5 px-4 text-right">Committed Spend</th>
                        <th class="py-3.5 px-4 text-right">Remaining Balance</th>
                        <th class="py-3.5 px-5">Utilization</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#c3c6d1]/20">
                    @forelse($budgets as $budget)
                    @php
                        $utilPct = $budget->budget_amount > 0 ? round(($budget->spent_amount / $budget->budget_amount) * 100, 1) : 0;
                        $remaining = max(0, $budget->budget_amount - $budget->spent_amount);
                        $isCritical = $utilPct >= 90;
                        $isWarning = $utilPct >= 75 && $utilPct < 90;
                    @endphp
                    <tr class="hover:bg-[#f8f9ff] transition-colors">
                        <!-- Cost Center Code -->
                        <td class="py-4 px-5">
                            <span class="font-mono font-bold text-[#00254e] bg-gray-100 px-2 py-1 rounded text-xs">
                                {{ $budget->cost_center }}
                            </span>
                        </td>

                        <!-- Department Name -->
                        <td class="py-4 px-4 font-bold text-[#111c2d]">
                            {{ $budget->department_name }}
                            <div class="text-[10px] text-[#737780] font-normal">Active Cost Pool FY{{ $budget->fiscal_year }}</div>
                        </td>

                        <!-- Total Budget -->
                        <td class="py-4 px-4 text-right font-bold text-[#111c2d]">
                            Rp {{ number_format($budget->budget_amount, 0, ',', '.') }}
                        </td>

                        <!-- Committed Spend -->
                        <td class="py-4 px-4 text-right font-semibold text-[#43474f]">
                            Rp {{ number_format($budget->spent_amount, 0, ',', '.') }}
                        </td>

                        <!-- Remaining Liquidity -->
                        <td class="py-4 px-4 text-right font-bold {{ $isCritical ? 'text-red-600' : 'text-emerald-700' }}">
                            Rp {{ number_format($remaining, 0, ',', '.') }}
                        </td>

                        <!-- Utilization Progress Bar -->
                        <td class="py-4 px-5 min-w-[160px]">
                            <div class="flex items-center justify-between text-[11px] font-bold mb-1">
                                <span class="{{ $isCritical ? 'text-red-700' : ($isWarning ? 'text-amber-700' : 'text-[#00254e]') }}">
                                    {{ $utilPct }}%
                                </span>
                                <span class="text-[10px] text-[#737780]">Cap 100%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div
                                    class="h-2 rounded-full {{ $isCritical ? 'bg-red-500' : ($isWarning ? 'bg-amber-500' : 'bg-[#00677e]') }}"
                                    style="width: {{ min(100, $utilPct) }}%"
                                ></div>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-4 text-center">
                            @if($isCritical)
                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-[10px] font-bold border border-red-200 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Critical (&gt;90%)
                                </span>
                            @elseif($isWarning)
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Attention
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Healthy
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            No departmental budget records found in database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-[#f0f3ff]/50 border-t border-[#c3c6d1]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-[#737780]">
            <div>
                Data sourced from table <code>department_budgets</code> (MySQL 8.0 / MariaDB compliant).
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                <span>Automatic budget validation during requisition creation</span>
            </div>
        </div>
    </div>
</div>
@endsection

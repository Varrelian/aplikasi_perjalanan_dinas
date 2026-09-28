@extends('layouts.app')

@section('title', 'Approvals & Decision Center')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-[#737780] text-[11px] font-semibold uppercase">
                <span>Operations & Control</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#00254e] font-bold">Approvals Center</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Plus_Jakarta_Sans'] text-[#00254e] tracking-tight mt-1">
                Approvals & Policy Decision Center
            </h1>
            <p class="text-xs sm:text-sm text-[#43474f] mt-0.5">
                Review employee travel requests, out-of-policy exception justifications, and departmental budget encumbrances.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-[#ffebee] text-[#ba1a1a] text-xs font-bold flex items-center gap-1.5 border border-[#e57373]/40">
                <span class="material-symbols-outlined text-[16px]">schedule</span>
                {{ $pendingApprovalsCount ?? 3 }} Requests Requiring Your Action
            </span>
        </div>
    </div>

    <!-- Master-Detail Table & Audit Split -->
    <div class="bg-white rounded-xl border border-[#c3c6d1]/40 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[#c3c6d1]/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#00677e] text-[20px]">fact_check</span>
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#00254e]">
                    Pending Approvals Queue
                </h2>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-1 bg-[#f0f3ff] p-1 rounded-lg text-xs font-semibold">
                <a href="?filter=all" class="px-3 py-1 rounded-md {{ !request('filter') || request('filter') === 'all' ? 'bg-[#00254e] text-white shadow-2xs' : 'text-[#43474f] hover:text-[#111c2d]' }}">
                    All Pending ({{ $pendingApprovalsCount ?? 0 }})
                </a>
                <a href="?filter=flagged" class="px-3 py-1 rounded-md {{ request('filter') === 'flagged' ? 'bg-[#00254e] text-white shadow-2xs' : 'text-[#43474f] hover:text-[#111c2d]' }}">
                    Flagged Exceptions ({{ $flaggedCount ?? 0 }})
                </a>
                <a href="?filter=high_value" class="px-3 py-1 rounded-md {{ request('filter') === 'high_value' ? 'bg-[#00254e] text-white shadow-2xs' : 'text-[#43474f] hover:text-[#111c2d]' }}">
                    High Value &gt; Rp 10M ({{ $highValueCount ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-[#f0f3ff] text-[#737780] uppercase tracking-wider text-[10px] font-bold border-b border-[#c3c6d1]/30">
                        <th class="py-3 px-4">Trip ID & Traveler</th>
                        <th class="py-3 px-4">Destination & Dates</th>
                        <th class="py-3 px-4">Policy Audit</th>
                        <th class="py-3 px-4 text-right">Encumbered Cost</th>
                        <th class="py-3 px-4">SLA Deadline</th>
                        <th class="py-3 px-4 text-center">Approve / Reject Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#c3c6d1]/20">
                    @forelse($pendingRequests ?? [] as $trip)
                    <tr class="hover:bg-[#f0f3ff]/40 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="font-mono font-bold text-[#00254e]">#{{ $trip->request_code ?? ('TRV-' . $trip->id) }}</div>
                            <div class="font-bold text-[#111c2d]">{{ optional($trip->traveler)->name ?? 'Corporate Traveler' }}</div>
                            <div class="text-[10px] text-[#737780]">{{ optional($trip->traveler)->job_title ?? 'Employee' }} ({{ optional($trip->traveler)->department ?? 'General' }})</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-[#111c2d]">{{ $trip->origin_code ?? $trip->origin }} &rarr; {{ $trip->dest_code ?? $trip->destination }}</div>
                            <div class="text-[10px] text-[#737780]">
                                @if($trip->departure_date)
                                    {{ \Carbon\Carbon::parse($trip->departure_date)->format('M d') }} &ndash; {{ \Carbon\Carbon::parse($trip->return_date)->format('M d, Y') }}
                                @else
                                    Dates not specified
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if ($trip->policy_status === 'compliant')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#e8f5e9] text-[#2e7d32]">
                                    <span class="material-symbols-outlined text-[12px]">check</span>
                                    Fully Compliant
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#fff3e0] text-[#e65100]">
                                    <span class="material-symbols-outlined text-[12px]">warning</span>
                                    Policy Exception
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="font-bold text-sm text-[#111c2d]">Rp {{ number_format($trip->total_cost, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-[#737780]">{{ $trip->cost_center ?? $trip->cost_center_id ?? 'CC-CORP' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="text-[11px] font-bold text-[#ba1a1a] flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">timer</span>
                                {{ $trip->approval_stage }}
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('approvals.decision', $trip->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#2e7d32] hover:bg-[#1b5e20] text-white text-[11px] font-bold shadow-2xs flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                        Authorize
                                    </button>
                                </form>

                                <form action="{{ route('approvals.decision', $trip->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#ba1a1a] hover:bg-[#8e0000] text-white text-[11px] font-bold shadow-2xs flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#737780]">
                            No pending approval requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($pendingRequests) && method_exists($pendingRequests, 'links'))
            <div class="p-4 border-t border-[#c3c6d1]/20">
                {{ $pendingRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

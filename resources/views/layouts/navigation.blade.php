<aside id="sidebar" class="fixed top-0 bottom-0 left-0 w-64 bg-white border-r border-[#c3c6d1]/30 shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    <div class="flex flex-col">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-[#c3c6d1]/20">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-left">
                <div class="w-8 h-8 rounded-lg bg-[#00254e] flex items-center justify-center text-white font-bold text-sm shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">flight_takeoff</span>
                </div>
                <div>
                    <span class="font-['Plus_Jakarta_Sans'] font-bold text-base tracking-tight text-[#00254e] block leading-none">
                        TravelSys
                    </span>
                    <span class="text-[9px] uppercase tracking-wider text-[#737780] font-semibold">
                        Enterprise Travel
                    </span>
                </div>
            </a>

            <button type="button" class="lg:hidden text-[#737780] hover:text-[#111c2d]" onclick="document.getElementById('sidebar').classList.add('-translate-x-full')">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-3 space-y-1">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-[#00254e] text-white shadow-xs' : 'text-[#43474f] hover:bg-[#f0f3ff] hover:text-[#111c2d]' }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#737780]' }}">dashboard</span>
                <span>Dashboard</span>
            </a>

            <!-- Trip Workspace (Trips Dashboard) -->
            <a href="{{ route('trips.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('trips.*') && !request()->routeIs('trips.create') ? 'bg-[#00254e] text-white shadow-xs' : 'text-[#43474f] hover:bg-[#f0f3ff] hover:text-[#111c2d]' }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('trips.*') && !request()->routeIs('trips.create') ? 'text-white' : 'text-[#737780]' }}">business_center</span>
                <span>Trip Workspace</span>
            </a>

            <!-- Travel Requests (Create Form) -->
            <a href="{{ route('trips.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('trips.create') ? 'bg-[#00254e] text-white shadow-xs' : 'text-[#43474f] hover:bg-[#f0f3ff] hover:text-[#111c2d]' }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('trips.create') ? 'text-white' : 'text-[#737780]' }}">edit_document</span>
                <span>Travel Requests</span>
            </a>

            <!-- Approvals Center -->
            <a href="{{ route('approvals.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('approvals.*') ? 'bg-[#00254e] text-white shadow-xs' : 'text-[#43474f] hover:bg-[#f0f3ff] hover:text-[#111c2d]' }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('approvals.*') ? 'text-white' : 'text-[#737780]' }}">verified</span>
                    <span>Approvals Center</span>
                </div>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-[#ba1a1a] text-white">
                    {{ $pendingApprovalsCount ?? 3 }}
                </span>
            </a>

            <!-- Section Divider -->
            <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-[#737780]">
                Budgets &amp; Finance
            </div>

            <!-- Budgets & Cost Centers -->
            <a href="{{ route('budgets.index') ?? '#' }}" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs font-medium text-[#43474f] hover:bg-[#f0f3ff] hover:text-[#111c2d] transition-all">
                <span class="material-symbols-outlined text-[18px] text-[#737780]">account_balance_wallet</span>
                <span>Department Budgets</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Account Panel -->
    <div class="p-4 border-t border-[#c3c6d1]/20 bg-[#f9f9ff]/80">
        <form method="POST" action="{{ route('logout') ?? '#' }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-medium text-[#ba1a1a] hover:bg-[#ffebee] rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>

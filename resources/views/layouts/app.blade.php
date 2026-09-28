<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f9f9ff]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Corporate Travel Management System') - {{ config('app.name', 'TravelSys') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Google Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS / Laravel Vite Directives -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full antialiased font-['Inter'] text-[#111c2d] bg-[#f9f9ff] flex flex-col min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Main Layout with Fixed Sidebar & Header -->
        <div class="flex flex-1">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:pl-64 transition-all duration-300">
                <!-- Top Header -->
                <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-md border-b border-[#c3c6d1]/30 shadow-[0_1px_8px_rgba(0,0,0,0.03)] flex items-center justify-between px-4 sm:px-6">
                    <!-- Left: Search & Mobile Hamburger -->
                    <div class="flex items-center gap-3 w-full max-w-md">
                        <button type="button" class="lg:hidden p-2 rounded-lg text-[#43474f] hover:bg-[#f0f3ff] transition-colors" onclick="document.getElementById('mobile-sidebar').classList.toggle('-translate-x-full')">
                            <span class="material-symbols-outlined text-[24px]">menu</span>
                        </button>

                        <form action="{{ route('trips.index') }}" method="GET" class="relative w-full">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#737780] text-[18px]">search</span>
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Traveler name, Trip ID (#TRV-...), or Destination"
                                class="w-full h-9 pl-9 pr-4 bg-[#f0f3ff] text-[#111c2d] placeholder:text-[#737780] text-xs rounded-lg outline-none border border-transparent focus:border-[#00677e] focus:bg-white transition-all"
                            />
                        </form>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 bg-[#dfe8ff] rounded-full text-xs text-[#111c2d] font-semibold">
                            <span class="w-2 h-2 rounded-full bg-[#00677e]"></span>
                            Role: {{ auth()->user()->role ?? 'Travel Manager / Employee' }}
                        </span>

                        <!-- Notification Bell & Role-Based Dropdown -->
                        <div class="relative inline-block" id="notifications-dropdown-container">
                            <button
                                type="button"
                                id="notifications-bell-btn"
                                onclick="document.getElementById('notifications-dropdown-menu').classList.toggle('hidden')"
                                class="relative flex items-center justify-center rounded-xl text-[#43474f] hover:text-[#111c2d] hover:bg-[#f0f3ff] transition-all cursor-pointer"
                                style="width: 40px; height: 40px;"
                                title="Notifications"
                                aria-label="Notifications"
                            >
                                <span class="material-symbols-outlined" style="font-size: 23px; line-height: 1;">notifications</span>
                                @if(($unreadNotificationsCount ?? 0) > 0)
                                    <span
                                        style="position: absolute; top: 1px; right: 1px; min-width: 18px; height: 18px; padding: 0 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; font-size: 10px; font-weight: 700; line-height: 1; color: #ffffff; background-color: #ba1a1a; border: 2px solid #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.15); pointer-events: none; z-index: 10;"
                                    >
                                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown Menu (Solid & Spacious Dimensions: 420px width, 480px height) -->
                            <div
                                id="notifications-dropdown-menu"
                                class="hidden absolute right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-[#c3c6d1]/50 flex flex-col z-50 overflow-hidden"
                                style="width: 420px; max-width: calc(100vw - 1.5rem); height: 480px;"
                            >
                                <!-- Dropdown Header (Spacious 60px height) -->
                                <div class="shrink-0 border-b border-[#c3c6d1]/20 flex items-center justify-between bg-white" style="height: 60px; padding: 0 20px;">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-['Plus_Jakarta_Sans'] font-bold text-[15px] text-[#00254e]">Notifications</span>
                                        @if(($unreadNotificationsCount ?? 0) > 0)
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#ba1a1a]/10 text-[#ba1a1a] whitespace-nowrap">
                                                {{ $unreadNotificationsCount }} unread
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 whitespace-nowrap">
                                                All caught up
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide uppercase whitespace-nowrap {{ str_contains(auth()->user()->role ?? '', 'Approver') || str_contains(auth()->user()->role ?? '', 'Manager') ? 'bg-[#00254e] text-white' : 'bg-[#e0f2fe] text-[#0369a1]' }}">
                                            {{ str_contains(auth()->user()->role ?? '', 'Approver') || str_contains(auth()->user()->role ?? '', 'Manager') ? 'Approver' : 'Employee' }}
                                        </span>
                                        @if(($unreadNotificationsCount ?? 0) > 0)
                                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0 p-0">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-[#00677e] hover:underline whitespace-nowrap cursor-pointer">
                                                    Mark all read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Notifications List (Fixed Scrollable Body with Generous Padding) -->
                                <div class="flex-1 overflow-y-auto divide-y divide-[#c3c6d1]/15 bg-white">
                                    @forelse($userNotifications ?? [] as $notification)
                                        @php
                                            $data = $notification->data;
                                            $isUnread = is_null($notification->read_at);
                                        @endphp
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="block m-0 p-0">
                                            @csrf
                                            <button type="submit" class="w-full text-left hover:bg-[#f0f3ff]/70 transition-all flex items-start cursor-pointer {{ $isUnread ? 'bg-[#f0f3ff]/35' : '' }}" style="padding: 16px 20px; gap: 14px;">
                                                <div class="rounded-xl flex items-center justify-center shrink-0 {{ $data['icon_color'] ?? 'text-[#00677e] bg-[#f0f3ff]' }}" style="width: 40px; height: 40px;">
                                                    <span class="material-symbols-outlined" style="font-size: 21px;">{{ $data['icon'] ?? 'notifications' }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span class="text-[13px] font-bold truncate {{ $isUnread ? 'text-[#00254e]' : 'text-[#333740]' }}">
                                                            {{ $data['title'] ?? 'Notification' }}
                                                        </span>
                                                        <span class="text-[11px] text-[#737780] shrink-0 font-medium whitespace-nowrap">
                                                            {{ $notification->created_at->diffForHumans(null, true, true) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-[12px] text-[#43474f] line-clamp-2 mt-1 leading-relaxed">
                                                        {{ $data['message'] ?? '' }}
                                                    </p>
                                                    <div class="flex items-center gap-2 mt-2.5 flex-wrap">
                                                        @if(isset($data['request_code']))
                                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-mono font-semibold bg-[#f0f3ff] text-[#00254e] border border-[#d2e0ff]">
                                                                #{{ $data['request_code'] }}
                                                            </span>
                                                        @endif
                                                        @if(isset($data['amount']) && $data['amount'] > 0)
                                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-mono font-semibold bg-[#e8f5e9] text-[#2e7d32] border border-[#c8e6c9]">
                                                                Rp {{ number_format($data['amount'], 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($isUnread)
                                                    <span style="width: 9px; height: 9px; border-radius: 9999px; background-color: #00677e; box-shadow: 0 0 0 3px rgba(0, 103, 126, 0.18); margin-top: 6px; flex-shrink: 0;" title="Unread"></span>
                                                @endif
                                            </button>
                                        </form>
                                    @empty
                                        <div class="h-full flex flex-col items-center justify-center p-8 text-center">
                                            <div class="w-14 h-14 rounded-2xl bg-[#f0f3ff] flex items-center justify-center text-[#737780] mb-3">
                                                <span class="material-symbols-outlined text-[32px] text-[#c3c6d1]">notifications_off</span>
                                            </div>
                                            <p class="text-sm font-bold text-[#111c2d]">No notifications yet</p>
                                            <p class="text-xs text-[#737780] mt-1 max-w-[240px] leading-relaxed">
                                                You are completely caught up with your travel requests and approvals.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Dropdown Footer (Spacious 44px height) -->
                                <div class="shrink-0 border-t border-[#c3c6d1]/20 bg-[#f9f9ff] flex items-center justify-between text-xs text-[#737780]" style="height: 44px; padding: 0 20px;">
                                    <span class="flex items-center gap-2">
                                        <span style="width: 7px; height: 7px; border-radius: 9999px;" class="{{ ($unreadNotificationsCount ?? 0) > 0 ? 'bg-[#ba1a1a]' : 'bg-emerald-500' }}"></span>
                                        <span class="font-medium">{{ $unreadNotificationsCount ?? 0 }} unread &bull; {{ $totalNotificationsCount ?? count($userNotifications ?? []) }} total</span>
                                    </span>
                                    <a href="{{ route('trips.index') }}" class="font-semibold text-[#00254e] hover:underline flex items-center gap-1.5 transition-colors">
                                        <span>Trip Workspace</span>
                                        <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown Trigger -->
                        <div class="flex items-center gap-2 pl-2 border-l border-[#c3c6d1]/40">
                            <div class="w-8 h-8 rounded-full bg-[#00254e] text-white flex items-center justify-center font-bold text-xs">
                                {{ substr(auth()->user()->name ?? 'Felix Gonelius', 0, 2) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-xs font-semibold text-[#111c2d]">{{ auth()->user()->name ?? 'Felix Gonelius' }}</div>
                                <div class="text-[10px] text-[#737780]">{{ auth()->user()->department ?? 'Technology' }}</div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content Injected Here -->
                <main class="flex-1 p-4 sm:p-6 max-w-7xl w-full mx-auto">
                    <!-- Session Status Alerts -->
                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-[#e8f5e9] border border-[#81c784] text-[#1b5e20] text-xs font-medium flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 rounded-lg bg-[#ffebee] border border-[#e57373] text-[#b71c1c] text-xs font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="mt-auto border-t border-[#c3c6d1]/20 bg-white px-6 py-4 text-center text-xs text-[#737780]">
                    &copy; {{ date('Y') }} TravelSys Corporate Mobility. Enterprise Travel Policy Enforcement & Clearance System.
                </footer>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            const container = document.getElementById('notifications-dropdown-container');
            const menu = document.getElementById('notifications-dropdown-menu');
            if (container && menu && !container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

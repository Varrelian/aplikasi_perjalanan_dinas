<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f9f9ff]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Corporate Login - {{ config('app.name', 'TravelSys') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased font-['Inter'] text-[#111c2d] bg-[#f9f9ff] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Brand Logo -->
        <div class="flex justify-center items-center gap-2.5">
            <div class="w-10 h-10 rounded-xl bg-[#00254e] flex items-center justify-center text-white shadow-md">
                <span class="material-symbols-outlined text-2xl">flight_takeoff</span>
            </div>
            <div>
                <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-xl tracking-tight text-[#00254e]">Travel<span class="text-[#00677e]">Sys</span></span>
                <span class="block text-[10px] uppercase tracking-widest text-[#737780] font-semibold">Enterprise Travel</span>
            </div>
        </div>

        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-[#00254e] font-['Plus_Jakarta_Sans']">
            Sign in to your corporate account
        </h2>
        <p class="mt-2 text-center text-xs text-[#737780]">
            Automated corporate travel clearance, policy auditing &amp; expense reconciliation
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-sm border border-[#c3c6d1]/40 rounded-2xl sm:rounded-3xl">
            <!-- Session Status / Errors -->
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span>Authentication Failed</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Corporate SSO Options -->
            <div class="space-y-2.5 mb-6">
                <button type="button" class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 border border-[#c3c6d1]/60 rounded-xl bg-white text-xs font-semibold text-[#111c2d] hover:bg-[#f0f3ff] transition-all shadow-xs">
                    <span class="material-symbols-outlined text-[18px] text-[#00677e]">corporate_fare</span>
                    <span>Single Sign-On (Corporate SAML / Okta)</span>
                </button>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[#c3c6d1]/30"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-[#737780]">or corporate email login</span>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Work Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#111c2d] mb-1">
                        Work Email Address
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#737780] text-[18px]">mail</span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email', 'andi.pratama@travelsys.internal') }}"
                            placeholder="name@company.com"
                            class="w-full h-10 pl-9 pr-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-[#111c2d]">
                            Password
                        </label>
                        <a href="#" class="text-[11px] font-semibold text-[#00677e] hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#737780] text-[18px]">lock</span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••••••"
                            class="w-full h-10 pl-9 pr-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="w-4 h-4 rounded text-[#00677e] focus:ring-[#00677e] border-gray-300"
                        >
                        <span class="text-xs text-[#43474f]">Remember this workstation (30 days)</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-11 bg-[#00677e] hover:bg-[#005366] text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span>Sign In to Dashboard</span>
                    </button>
                </div>
            </form>

            <!-- Demo Quick Login Presets -->
            <div class="mt-6 pt-5 border-t border-[#c3c6d1]/30">
                <p class="text-[11px] font-bold text-[#737780] uppercase tracking-wider mb-2.5">
                    Fast Demo Accounts
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        onclick="document.getElementById('email').value='andi.pratama@travelsys.internal'; document.getElementById('password').value='secret123';"
                        class="p-2 text-left rounded-lg bg-[#f0f3ff] hover:bg-[#dfe8ff] text-[11px] transition-colors"
                    >
                        <p class="font-bold text-[#00254e]">Andi Pratama</p>
                        <p class="text-[#737780]">Sr. Consultant (Traveler)</p>
                    </button>
                    <button
                        type="button"
                        onclick="document.getElementById('email').value='siti.rahma@travelsys.internal'; document.getElementById('password').value='secret123';"
                        class="p-2 text-left rounded-lg bg-[#f0f3ff] hover:bg-[#dfe8ff] text-[11px] transition-colors"
                    >
                        <p class="font-bold text-[#00254e]">Siti Rahma</p>
                        <p class="text-[#737780]">VP Product (Approver)</p>
                    </button>
                </div>
            </div>

            <!-- Footer: Register Link -->
            <p class="mt-6 text-center text-xs text-[#737780]">
                New team member?
                <a href="{{ route('register') }}" class="font-bold text-[#00677e] hover:underline ml-1">
                    Register corporate profile
                </a>
            </p>
        </div>

        <div class="mt-6 text-center text-[11px] text-[#737780] flex items-center justify-center gap-3">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">lock</span> 256-bit TLS Encrypted</span>
            <span>&bull;</span>
            <span>Travel Policy Engine v2.4</span>
            <span>&bull;</span>
            <span>SOC-2 Certified</span>
        </div>
    </div>
</body>
</html>

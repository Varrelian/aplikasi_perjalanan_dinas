<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f9f9ff]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Employee Registration - {{ config('app.name', 'TravelSys') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased font-['Inter'] text-[#111c2d] bg-[#f9f9ff] flex flex-col justify-center py-10 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <!-- Brand Logo -->
        <div class="flex justify-center items-center gap-2.5">
            <div class="w-10 h-10 rounded-xl bg-[#00254e] flex items-center justify-center text-white shadow-md">
                <span class="material-symbols-outlined text-2xl">person_add</span>
            </div>
            <div>
                <span class="font-['Plus_Jakarta_Sans'] font-extrabold text-xl tracking-tight text-[#00254e]">Travel<span class="text-[#00677e]">Sys</span></span>
                <span class="block text-[10px] uppercase tracking-widest text-[#737780] font-semibold">Corporate Onboarding</span>
            </div>
        </div>

        <h2 class="mt-5 text-center text-2xl font-bold tracking-tight text-[#00254e] font-['Plus_Jakarta_Sans']">
            Create your employee travel profile
        </h2>
        <p class="mt-1 text-center text-xs text-[#737780]">
            Automatic travel band allocation, per-diem rules, and corporate card routing
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-sm border border-[#c3c6d1]/40 rounded-2xl sm:rounded-3xl">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <div class="font-bold flex items-center gap-1.5 mb-1">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span>Registration Errors</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name & Employee ID in 2 columns -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Full Legal Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name') }}"
                            placeholder="e.g. Dimas Wicaksono"
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>

                    <div>
                        <label for="employee_code" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Employee ID <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="employee_code"
                            name="employee_code"
                            type="text"
                            required
                            value="{{ old('employee_code', 'EMP-' . rand(1000, 9999)) }}"
                            placeholder="e.g. EMP-9821"
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all font-mono"
                        >
                    </div>
                </div>

                <!-- Corporate Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#111c2d] mb-1">
                        Work Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#737780] text-[18px]">mail</span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                            placeholder="dimas.wicaksono@travelsys.internal"
                            class="w-full h-10 pl-9 pr-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Department & Job Title -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="department"
                            name="department"
                            required
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all cursor-pointer"
                        >
                            <option value="">Select Department...</option>
                            <option value="Engineering & Tech" {{ old('department') == 'Engineering & Tech' ? 'selected' : '' }}>Engineering & Tech</option>
                            <option value="Product & Design" {{ old('department') == 'Product & Design' ? 'selected' : '' }}>Product & Design</option>
                            <option value="Corporate Finance" {{ old('department') == 'Corporate Finance' ? 'selected' : '' }}>Corporate Finance</option>
                            <option value="Global Operations" {{ old('department') == 'Global Operations' ? 'selected' : '' }}>Global Operations</option>
                            <option value="Enterprise Sales" {{ old('department') == 'Enterprise Sales' ? 'selected' : '' }}>Enterprise Sales</option>
                            <option value="Executive Management" {{ old('department') == 'Executive Management' ? 'selected' : '' }}>Executive Management</option>
                        </select>
                    </div>

                    <div>
                        <label for="job_title" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Job Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="job_title"
                            name="job_title"
                            type="text"
                            required
                            value="{{ old('job_title') }}"
                            placeholder="e.g. Lead Cloud Architect"
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Band / Level -->
                <div>
                    <label for="band" class="block text-xs font-bold text-[#111c2d] mb-1">
                        Corporate Travel Band / Tier <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="band"
                        name="band"
                        required
                        class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all cursor-pointer"
                    >
                        <option value="Band 1" {{ old('band') == 'Band 1' ? 'selected' : '' }}>Band 1 - Associate / Specialist (Economy Domestic, Standard Hotels)</option>
                        <option value="Band 2" {{ old('band', 'Band 2') == 'Band 2' ? 'selected' : '' }}>Band 2 - Senior / Consultant (Flexible Economy, 4-Star Hotel Cap)</option>
                        <option value="Band 3" {{ old('band') == 'Band 3' ? 'selected' : '' }}>Band 3 - Principal / Manager (Premium Economy, Business Class &gt; 6h)</option>
                        <option value="Band 4" {{ old('band') == 'Band 4' ? 'selected' : '' }}>Band 4 - VP / Director (Approver Authority, Business Class Global)</option>
                        <option value="Band 5" {{ old('band') == 'Band 5' ? 'selected' : '' }}>Band 5 - C-Suite Executive (Executive Authorization Clearance)</option>
                    </select>
                </div>

                <!-- Password & Confirmation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            placeholder="Minimum 8 characters"
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-[#111c2d] mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            placeholder="Repeat password"
                            class="w-full h-10 px-3 text-xs bg-[#f0f3ff] border border-transparent focus:border-[#00677e] focus:bg-white rounded-xl outline-none transition-all"
                        >
                    </div>
                </div>

                <!-- Corporate Travel Guidelines Acknowledgment -->
                <div class="pt-2">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            required
                            name="terms"
                            class="mt-0.5 w-4 h-4 rounded text-[#00677e] focus:ring-[#00677e] border-gray-300"
                        >
                        <span class="text-[11px] text-[#43474f] leading-tight">
                            I agree to abide by corporate travel policies, expense reimbursement limits, and SLA pre-approval guidelines.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-11 bg-[#00677e] hover:bg-[#005366] text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        <span>Complete Registration &amp; Open Workspace</span>
                    </button>
                </div>
            </form>

            <!-- Footer: Login Link -->
            <p class="mt-6 text-center text-xs text-[#737780]">
                Already have an enterprise account?
                <a href="{{ route('login') }}" class="font-bold text-[#00677e] hover:underline ml-1">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</body>
</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Show the corporate login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Authentication successful.',
                    'user'    => Auth::user(),
                    'redirect'=> route('dashboard'),
                ]);
            }

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Welcome back, ' . Auth::user()->name . '!');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our corporate directory records.',
            ], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our corporate directory records.',
        ])->onlyInput('email');
    }

    /**
     * Show the corporate employee registration form.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming employee registration request.
     */
    public function register(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'employee_code' => ['required', 'string', 'max:50', 'unique:users,employee_code'],
            'job_title'     => ['required', 'string', 'max:100'],
            'department'    => ['required', 'string', 'max:100'],
            'band'          => ['required', 'string', 'in:Band 1,Band 2,Band 3,Band 4,Band 5'],
            'password'      => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'employee_code' => strtoupper($validated['employee_code']),
            'job_title'     => $validated['job_title'],
            'department'    => $validated['department'],
            'band'          => $validated['band'],
            'role'          => in_array($validated['band'], ['Band 4', 'Band 5']) ? 'Approver / Director' : 'Employee / Traveler',
            'avatar_url'    => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
        ]);

        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Corporate account successfully registered and activated.',
                'user'    => $user,
                'redirect'=> route('dashboard'),
            ], 201);
        }

        return redirect(route('dashboard'))
            ->with('status', 'Registration complete. Your corporate travel profile is active.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
                'redirect'=> route('login'),
            ]);
        }

        return redirect()->route('login');
    }
}

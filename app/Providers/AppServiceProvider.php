<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('layouts.navigation', function ($view) {
            try {
                if (auth()->check()) {
                    $user = auth()->user();
                    $isApprover = str_contains($user->role ?? '', 'Approver')
                               || str_contains($user->role ?? '', 'Manager')
                               || in_array($user->band ?? '', ['Band 4', 'Band 5']);

                    if ($isApprover) {
                        $count = \App\Models\TravelRequest::where(function ($q) {
                            $q->where('approval_stage', 'like', '%Pending%')
                              ->orWhere('approval_stage', 'like', '%Review%')
                              ->orWhere('approval_stage', 'like', '%Manager%')
                              ->orWhere('approval_stage', 'like', '%Director%');
                        })->count();
                    } else {
                        $count = 0;
                    }
                } else {
                    $count = 0;
                }
                $view->with('pendingApprovalsCount', $count);
            } catch (\Throwable $e) {
                $view->with('pendingApprovalsCount', 0);
            }
        });

        view()->composer(['layouts.app', 'layouts.navigation'], function ($view) {
            try {
                if (auth()->check()) {
                    $user = auth()->user();
                    $view->with([
                        'userNotifications'        => $user->notifications()->latest()->take(15)->get(),
                        'unreadNotificationsCount' => $user->unreadNotifications()->count(),
                        'totalNotificationsCount'  => $user->notifications()->count(),
                    ]);
                } else {
                    $view->with([
                        'userNotifications'        => collect(),
                        'unreadNotificationsCount' => 0,
                        'totalNotificationsCount'  => 0,
                    ]);
                }
            } catch (\Throwable $e) {
                $view->with([
                    'userNotifications'        => collect(),
                    'unreadNotificationsCount' => 0,
                    'totalNotificationsCount'  => 0,
                ]);
            }
        });
    }
}

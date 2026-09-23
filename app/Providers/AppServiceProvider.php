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
                $count = \App\Models\TravelRequest::where(function ($q) {
                    $q->where('approval_stage', 'like', '%Pending%')
                      ->orWhere('approval_stage', 'like', '%Review%')
                      ->orWhere('approval_stage', 'like', '%Manager%')
                      ->orWhere('approval_stage', 'like', '%Director%');
                })->count();
                $view->with('pendingApprovalsCount', $count);
            } catch (\Throwable $e) {
                $view->with('pendingApprovalsCount', 0);
            }
        });
    }
}

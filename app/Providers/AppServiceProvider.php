<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Event;
use App\Policies\TaskPolicy;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class  => TaskPolicy::class,
        Event::class => EventPolicy::class,
    ];
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
        Vite::prefetch(concurrency: 3);

        Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin() || $user->isEmployee();
        });

        Gate::define('manage-all-tasks', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-all-events', function ($user) {
            return $user->isAdmin();
        });
    }
}

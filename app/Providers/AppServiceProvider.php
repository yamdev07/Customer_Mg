<?php

namespace App\Providers;

use App\Models\Activite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        // Cloche de notifications : injecte les dernières activités dans le layout.
        View::composer('layouts.app', function ($view) {
            $recentes = collect();

            if (Auth::check() && Schema::hasTable('activites')) {
                $recentes = Activite::with('user')->latest()->limit(8)->get();
            }

            $view->with('notifRecentes', $recentes);
        });
    }
}

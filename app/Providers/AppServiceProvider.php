<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'auth_data' => function () {
                if (Auth::check()) {
                    $user = Auth::user();
                    Log::info('Authenticated user:', ['user' => $user]);
                    return [
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'roles' => $user->userRoles->pluck('name'),
                        ],
                    ];
                } else {
                    Log::info('No user is authenticated');
                    return [
                        'user' => null,
                    ];
                }
            },
        ]);
    }
}

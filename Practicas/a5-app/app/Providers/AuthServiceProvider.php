<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => AdminPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-admin-dashboard', function (User $user) {
            dump('Evaluando Gate para:', $user->id);
            dump('Es admin:', $user->isAdmin());
            return $user->isAdmin();
        });
        
    }
}


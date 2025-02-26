<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    // Map User model to UserPolicy
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define a gate to allow only admins to create users
        Gate::define('create', function (User $user) {
            return strtolower($user->role) === 'admin'; // Case insensitive check
        });
    }
}
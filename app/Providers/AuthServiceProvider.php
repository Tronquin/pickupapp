<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        /**
         * ACL Gates for type user permission
         */
        Gate::define('isAdmin', function ($user) {
            return $user->type === 'admin';
        });
        Gate::define('isDeveloper', function ($user) {
            return $user->type === 'dev';
        });
        Gate::define('isEmployee', function ($user) {
            return $user->type === 'employee';
        });
        Gate::define('isDelivery', function ($user) {
            return $user->type === 'delivery';
        });
        Gate::define('isUser', function ($user) {
            return $user->type === 'user';
        });

        Passport::routes();

        //
    }
}

<?php

namespace Activelogiclabs\Administration\Providers;

use Activelogiclabs\Administration\Policies\AgentPolicy;
use Activelogiclabs\Administration\Policies\SystemPolicy;
use App\Agent;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class AdminAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
//        Agent::class => AgentPolicy::class
    ];

    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
    }

    public function register()
    {
        Gate::define('view-page', function($user) {

            return true;

        });
    }
}
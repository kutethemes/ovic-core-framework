<?php

namespace Ovic\Framework;

use App\Policies\PostPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class FrameworkAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'  => 'App\Policies\ModelPolicy',
        Roles::class => RolesPolicy::class,
        Users::class => UsersPolicy::class,
    ];

    /**
     * Register all modules.
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('permission', Permission::class);
    }

    /**
     * Booting the package.
     */
    public function boot( Request $request )
    {
        $this->registerPolicies();
    }
}

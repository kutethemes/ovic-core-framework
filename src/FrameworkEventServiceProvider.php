<?php

namespace Ovic\Framework;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class FrameworkEventServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The event listener mappings for the application.
     * https://laravel.com/docs/6.x/authentication
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login'  => [
            'Ovic\Framework\UserListenersHandler@login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'Ovic\Framework\UserListenersHandler@logout',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Ovic\Framework\UserEventHandler',
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
